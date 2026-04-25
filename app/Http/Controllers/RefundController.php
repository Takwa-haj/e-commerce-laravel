<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RefundRequest;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Refund;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundStatusUpdated;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RefundController extends Controller
{
    /**
     * Show refund request form
     */
    public function showRefundForm(Request $request, $order_id)
    {
        $order = Order::with(['orderItems.product', 'orderItems' => function($query) {
            $query->where('refund_status', 'none');
        }])->findOrFail($order_id);

        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        if ($order->status != 'delivered') {
            return redirect()->route('user.order.details', $order_id)
                ->with('error', 'Les remboursements ne sont disponibles que pour les commandes livrées.');
        }

        $days_since_delivery = $order->delivered_date ? now()->diffInDays(Carbon::parse($order->delivered_date)) : 999;

        $refundable_amount = $order->orderItems->where('refund_status', 'none')
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });

        $existing_refunds = RefundRequest::where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $settings = Setting::first();

        return view('user.refund-request', compact(
            'order',
            'days_since_delivery',
            'refundable_amount',
            'existing_refunds',
            'settings'
        ));
    }

    /**
     * Submit refund request
     */
    public function requestRefund(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_items' => 'required|array|min:1',
            'order_items.*' => 'exists:order_items,id',
            'reason_type' => 'required|in:defective,wrong,damaged,other',
            'reason_details' => 'required|string|max:500',
            'additional_info' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array|max:2',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'quantities' => 'nullable|array',
            'quantities.*' => 'integer|min:1'
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($order->status != 'delivered') {
            return response()->json(['error' => 'Refunds are only available for delivered orders'], 400);
        }

        $days_since_delivery = $order->delivered_date ? now()->diffInDays(Carbon::parse($order->delivered_date)) : 999;
        if ($days_since_delivery > 14) {
            return response()->json(['error' => 'Refund period (14 days) has expired'], 400);
        }

        $requested_amount = 0;
        $order_items = [];

        foreach ($request->order_items as $item_id) {
            $order_item = OrderItem::find($item_id);

            if ($order_item->order_id != $order->id) {
                continue;
            }

            if ($order_item->refund_status != 'none') {
                return response()->json(['error' => "Item {$order_item->product->name} has already been refunded"], 400);
            }

            $quantity = $request->quantities[$item_id] ?? $order_item->quantity;
            $requested_amount += $order_item->price * $quantity;

            $order_items[] = [
                'id' => $item_id,
                'quantity' => $quantity,
                'price' => $order_item->price
            ];
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('refunds/attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
            }
        }

        DB::beginTransaction();
        try {
            $refund_request = RefundRequest::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_item_ids' => array_column($order_items, 'id'),
                'quantities' => array_column($order_items, 'quantity'),
                'reason_type' => $request->reason_type,
                'reason_details' => $request->reason_details,
                'additional_info' => $request->additional_info,
                'attachments' => $attachments,
                'requested_amount' => $requested_amount,
                'status' => 'pending',
            ]);

            // Attach order items to refund request
            foreach ($order_items as $item_data) {
                $refund_request->orderItems()->attach($item_data['id'], [
                    'quantity' => $item_data['quantity'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Update order items refund status
            foreach ($request->order_items as $item_id) {
                $quantity = $request->quantities[$item_id] ?? OrderItem::find($item_id)->quantity;

                OrderItem::where('id', $item_id)->update([
                    'refund_status' => 'requested',
                    'refund_requested_at' => now(),
                    'refund_reason' => $request->reason_details,
                    'refund_amount' => OrderItem::find($item_id)->price * $quantity
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request submitted successfully',
                'refund_id' => $refund_request->id,
                'amount' => number_format($requested_amount, 2)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund request error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to submit refund request'], 500);
        }
    }

    /**
     * View refund details
     */
    public function viewRefundDetails($refund_id)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $refund = RefundRequest::with(['order', 'orderItems.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($refund_id);

        return view('user.refund-details', compact('refund','user'));
    }

    /**
     * Get user's refund requests
     */
    public function getUserRefunds()
    {
        $refunds = RefundRequest::where('user_id', Auth::id())
            ->with(['order', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.refunds', compact('refunds'));
    }

    /**
     * Get all refund requests for admin
     */
    public function getAdminRefunds(Request $request)
    {
        $query = RefundRequest::with([
            'order.user',
            'orderItems.product'
        ]);

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('id', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('order.user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $refunds = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.refunds', compact('refunds'));
    }

    /**
     * Process refund (Admin only)
     */
    public function processRefund(Request $request)
    {
        $request->validate([
            'refund_id' => 'required|exists:refund_requests,id',
            'action' => 'required|in:approve,reject,edit,process,complete,cancel',
            'admin_notes' => 'nullable|string|max:500',
            'refund_amount' => 'nullable|numeric|min:0'
        ]);

        $refundRequest = RefundRequest::findOrFail($request->refund_id);

        switch ($request->action) {
            case 'approve':
                return $this->approveRefundRequest($refundRequest, $request);
            case 'reject':
                return $this->rejectRefundRequest($refundRequest, $request);
            case 'process':
                return $this->processRefundRequest($refundRequest, $request);
            case 'complete':
                return $this->completeRefundRequest($refundRequest, $request);
            case 'cancel':
                return $this->cancelRefundRequest($refundRequest, $request);
            case 'edit':
                return $this->editRefundRequest($refundRequest, $request);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }

   /**
 * Update refund request (Admin only)
 */
public function updateRefund(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,under_review,approved,rejected,processing,completed,cancelled',
        'admin_notes' => 'nullable|string|max:500',
        'refund_amount' => 'nullable|numeric|min:0'
    ]);

    DB::beginTransaction();
    try {
        $refundRequest = RefundRequest::findOrFail($id);
        $oldStatus = $refundRequest->status;
        $newStatus = $request->status;

        // Validate status transition
        $validTransitions = [
            'pending' => ['under_review', 'approved', 'rejected', 'cancelled'],
            'under_review' => ['approved', 'rejected', 'cancelled'],
            'approved' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'rejected' => ['cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        if (!in_array($newStatus, $validTransitions[$oldStatus])) {
            return response()->json([
                'error' => 'Transition from ' . $oldStatus . ' to ' . $newStatus . ' is not allowed'
            ], 400);
        }

        // Update refund request
        $refundRequest->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes ?? $refundRequest->admin_notes,
            'refund_amount' => $request->refund_amount ?? $refundRequest->refund_amount,
            'processed_at' => in_array($newStatus, ['approved', 'rejected', 'processing', 'completed', 'cancelled']) ? now() : null
        ]);

        // Update related order items
        foreach ($refundRequest->orderItems as $orderItem) {
            $orderItem->update([
                'refund_status' => $newStatus,
                'admin_notes' => $request->admin_notes ?? $orderItem->admin_notes,
                'refund_processed_at' => in_array($newStatus, ['approved', 'rejected', 'processing', 'completed', 'cancelled']) ? now() : null
            ]);
        }

        DB::commit();

        // Send email notification (outside transaction to avoid lock)
        $this->sendRefundNotification($refundRequest->fresh(), $newStatus);

        return response()->json([
            'success' => true,
            'message' => 'Refund request updated successfully',
            'refund' => $refundRequest->fresh()
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Update refund error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to update refund request: ' . $e->getMessage()], 500);
    }
}
    /**
     * Quick action for refund (without modal)
     */
    public function quickAction(Request $request)
    {
        $request->validate([
            'refund_id' => 'required|exists:refund_requests,id',
            'action' => 'required|in:process,complete,cancel'
        ]);

        $refundRequest = RefundRequest::findOrFail($request->refund_id);

        switch ($request->action) {
            case 'process':
                return $this->processRefundRequest($refundRequest, $request);
            case 'complete':
                return $this->completeRefundRequest($refundRequest, $request);
            case 'cancel':
                return $this->cancelRefundRequest($refundRequest, $request);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }

    /**
     * Approve refund request
     */
    private function approveRefundRequest($refundRequest, $request)
    {
        DB::beginTransaction();
        try {
            $refund_amount = $request->refund_amount ?? $refundRequest->requested_amount;

            if ($refund_amount > $refundRequest->requested_amount) {
                return response()->json(['error' => 'Le montant ne peut pas dépasser le montant demandé'], 400);
            }

            $refundRequest->update([
                'status' => 'approved',
                'refund_amount' => $refund_amount,
                'admin_notes' => $request->admin_notes,
                'processed_at' => now()
            ]);

            // Update order items
            foreach ($refundRequest->orderItems as $orderItem) {
                $pivot_quantity = $orderItem->pivot->quantity ?? $orderItem->quantity;
                $refund_amount_per_item = ($orderItem->price * $pivot_quantity);

                $orderItem->update([
                    'refund_status' => 'approved',
                    'refund_amount' => $refund_amount_per_item,
                    'admin_notes' => $request->admin_notes
                ]);
            }

            // Send email notification
            $this->sendRefundNotification($refundRequest, 'approved');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request approved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to approve refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject refund request
     */
    private function rejectRefundRequest($refundRequest, $request)
    {
        DB::beginTransaction();
        try {
            $refundRequest->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'processed_at' => now()
            ]);

            // Update order items
            foreach ($refundRequest->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'rejected',
                    'admin_notes' => $request->admin_notes
                ]);
            }

            // Send email notification
            $this->sendRefundNotification($refundRequest, 'rejected');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request rejected'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to reject refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process refund via Stripe
     */
    private function processRefundRequest($refundRequest, $request)
    {
        DB::beginTransaction();
        try {
            $order = $refundRequest->order;
            $transaction = $order->transaction;

            if (!$transaction || $transaction->mode != 'card' || !$transaction->stripe_payment_id) {
                return response()->json(['error' => 'Refund can only be processed for Stripe payments'], 400);
            }

            // Initialize Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            $refund_amount = $refundRequest->refund_amount ?? $refundRequest->requested_amount;

            // Process refund via Stripe
            $refund = Refund::create([
                'payment_intent' => $transaction->stripe_payment_id,
                'amount' => $this->toCents($refund_amount),
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_id' => $order->id,
                    'refund_request_id' => $refundRequest->id,
                ]
            ]);

            $refundRequest->update([
                'status' => 'processing',
                'stripe_refund_id' => $refund->id,
                'admin_notes' => $request->admin_notes ?? 'Refund processed via Stripe',
                'processed_at' => now()
            ]);

            // Update order items
            foreach ($refundRequest->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'processing',
                    'stripe_refund_id' => $refund->id,
                    'refund_processed_at' => now()
                ]);
            }

            // Send email notification
            $this->sendRefundNotification($refundRequest, 'processing');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund is being processed via Stripe',
                'refund_id' => $refund->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stripe refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Complete refund request
     */
    private function completeRefundRequest($refundRequest, $request)
    {
        DB::beginTransaction();
        try {
            $refundRequest->update([
                'status' => 'completed',
                'admin_notes' => $request->admin_notes ?? 'Refund completed',
                'processed_at' => now()
            ]);

            // Update order items
            foreach ($refundRequest->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'completed',
                    'refund_processed_at' => now()
                ]);
            }

            // Update order totals
            $this->updateOrderAfterRefund($refundRequest->order);

            // Send email notification
            $this->sendRefundNotification($refundRequest, 'completed');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund marked as completed'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to complete refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cancel refund request
     */
    private function cancelRefundRequest($refundRequest, $request)
    {
        DB::beginTransaction();
        try {
            $refundRequest->update([
                'status' => 'cancelled',
                'admin_notes' => $request->admin_notes ?? 'Cancelled by admin',
                'processed_at' => now()
            ]);

            // Update order items back to none
            foreach ($refundRequest->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'none',
                    'refund_requested_at' => null,
                    'refund_reason' => null,
                    'refund_amount' => null,
                    'stripe_refund_id' => null,
                    'refund_processed_at' => null,
                    'admin_notes' => $request->admin_notes
                ]);
            }

            // Send email notification
            $this->sendRefundNotification($refundRequest, 'cancelled');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request cancelled'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to cancel refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Edit refund request
     */
    private function editRefundRequest($refundRequest, $request)
    {
        try {
            $refundRequest->update([
                'admin_notes' => $request->admin_notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request updated'
            ]);

        } catch (\Exception $e) {
            Log::error('Edit refund error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update refund: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete refund request (Admin only)
     */
    public function deleteRefund(Request $request, $id)
    {
        try {
            $refundRequest = RefundRequest::findOrFail($id);

            // Check if refund is in a state that can be deleted
            if (!in_array($refundRequest->status, ['cancelled', 'rejected'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only cancelled or rejected refunds can be deleted.'
                ], 400);
            }

            // Update order items first
            foreach ($refundRequest->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'none',
                    'refund_requested_at' => null,
                    'refund_reason' => null,
                    'refund_amount' => null,
                    'stripe_refund_id' => null,
                    'refund_processed_at' => null,
                    'admin_notes' => null
                ]);
            }

            // Delete the refund request
            $refundRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Refund request deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete refund error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete refund request'
            ], 500);
        }
    }

    /**
     * Get refund status for user
     */
    public function getRefundStatus($refund_id)
    {
        try {
            $refund = RefundRequest::where('user_id', Auth::id())
                ->with(['order', 'orderItems.product'])
                ->findOrFail($refund_id);

            return response()->json([
                'success' => true,
                'refund' => [
                    'id' => $refund->id,
                    'status' => $refund->status,
                    'status_label' => $refund->getStatusLabel(),
                    'status_color' => $this->getStatusColor($refund->status),
                    'requested_amount' => $refund->requested_amount,
                    'refund_amount' => $refund->refund_amount,
                    'admin_notes' => $refund->admin_notes,
                    'processed_at' => $refund->processed_at ? $refund->processed_at->format('d/m/Y H:i') : null,
                    'stripe_refund_id' => $refund->stripe_refund_id,
                    'order_id' => $refund->order_id,
                    'created_at' => $refund->created_at->format('d/m/Y H:i')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Refund not found'
            ], 404);
        }
    }

    /**
     * Get refund statistics for dashboard
     */
    public function getRefundStats()
    {
        $stats = [
            'total' => RefundRequest::count(),
            'pending' => RefundRequest::where('status', 'pending')->count(),
            'under_review' => RefundRequest::where('status', 'under_review')->count(),
            'approved' => RefundRequest::where('status', 'approved')->count(),
            'processing' => RefundRequest::where('status', 'processing')->count(),
            'completed' => RefundRequest::where('status', 'completed')->count(),
            'rejected' => RefundRequest::where('status', 'rejected')->count(),
            'cancelled' => RefundRequest::where('status', 'cancelled')->count(),
        ];

        // Total refunded amount
        $stats['total_refunded'] = RefundRequest::where('status', 'completed')
            ->sum('refund_amount');

        // Monthly refunds
        $stats['monthly_refunds'] = RefundRequest::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('refund_amount');

        return response()->json($stats);
    }

    /**
     * Send refund notification email
     */
    private function sendRefundNotification($refundRequest, $status)
    {
        try {
            $user = $refundRequest->user;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new RefundStatusUpdated($refundRequest, $status));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send refund notification: ' . $e->getMessage());
        }
    }

    /**
     * Update order totals after refund
     */
    private function updateOrderAfterRefund($order)
    {
        $totalRefunded = OrderItem::where('order_id', $order->id)
            ->where('refund_status', 'completed')
            ->sum('refund_amount');

        $order->total_refunded = $totalRefunded;
        $order->save();
    }

    /**
     * Convert amount to cents for Stripe
     */
    private function toCents($amount)
    {
        return round(floatval($amount) * 100);
    }

    /**
     * Cancel refund (user initiated)
     */
    public function cancelRefund(Request $request, $refund_id)
    {
        DB::beginTransaction();
        try {
            $refund = RefundRequest::where('user_id', Auth::id())
                ->where('id', $refund_id)
                ->firstOrFail();

            if (!in_array($refund->status, ['pending', 'under_review'])) {
                return response()->json([
                    'error' => 'Cette demande ne peut plus être annulée.'
                ], 400);
            }

            // Update order items
            foreach ($refund->orderItems as $orderItem) {
                $orderItem->update([
                    'refund_status' => 'none',
                    'refund_requested_at' => null,
                    'refund_reason' => null,
                    'refund_amount' => null,
                    'stripe_refund_id' => null,
                    'refund_processed_at' => null,
                    'admin_notes' => null
                ]);
            }

            $refund->update([
                'status' => 'cancelled',
                'admin_notes' => 'Annulé par l\'utilisateur le ' . now()->format('d/m/Y H:i')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Demande de remboursement annulée avec succès.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User cancel refund error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'annulation.'
            ], 500);
        }
    }

    /**
     * Get refund details for admin modal
     */
    public function getRefundDetails($id)
    {
        try {
            $refund = RefundRequest::with(['order.user', 'orderItems.product'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'refund' => $refund,
                'order_items' => $refund->orderItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'name' => $item->product->name,
                            'image' => $item->product->image,
                            'sku' => $item->product->SKU
                        ],
                        'quantity' => $item->pivot->quantity ?? $item->quantity,
                        'price' => $item->price,
                        'total' => ($item->pivot->quantity ?? $item->quantity) * $item->price
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Get refund details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load refund details'
            ], 500);
        }
    }

    /**
     * Export refunds
     */
    public function exportRefunds(Request $request)
    {
        $format = $request->get('format', 'csv');
        $refunds = RefundRequest::with(['order.user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'excel') {
            // Return Excel file
            // You'll need to implement Excel export logic here
            return response()->download(
                $this->generateExcel($refunds),
                'refunds_' . date('Y-m-d') . '.xlsx'
            );
        } else {
            // Return CSV file
            return response()->download(
                $this->generateCSV($refunds),
                'refunds_' . date('Y-m-d') . '.csv'
            );
        }
    }

    /**
     * Generate CSV file
     */
    private function generateCSV($refunds)
    {
        $filename = storage_path('app/refunds_' . time() . '.csv');
        $handle = fopen($filename, 'w');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Order ID',
            'Customer',
            'Email',
            'Requested Amount',
            'Refund Amount',
            'Status',
            'Reason',
            'Created At',
            'Processed At',
            'Stripe Refund ID'
        ]);

        // Add data rows
        foreach ($refunds as $refund) {
            fputcsv($handle, [
                $refund->id,
                $refund->order_id,
                $refund->order->user->name ?? 'N/A',
                $refund->order->user->email ?? 'N/A',
                $refund->requested_amount,
                $refund->refund_amount,
                $refund->status,
                $refund->reason_type,
                $refund->created_at->format('Y-m-d H:i:s'),
                $refund->processed_at ? $refund->processed_at->format('Y-m-d H:i:s') : '',
                $refund->stripe_refund_id
            ]);
        }

        fclose($handle);
        return $filename;
    }

    /**
     * Generate Excel file (placeholder)
     */
    private function generateExcel($refunds)
    {
        // Implement Excel generation logic here
        // For now, return CSV as well
        return $this->generateCSV($refunds);
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'warning',
            'under_review' => 'info',
            'approved' => 'primary',
            'rejected' => 'danger',
            'processing' => 'secondary',
            'completed' => 'success',
            'cancelled' => 'dark',
            default => 'secondary',
        };
    }




}
