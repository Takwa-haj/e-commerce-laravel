<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailLog;

class LogEmails
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent
     */
    public function terminate($request, $response)
    {
        // Log email sending if applicable
        if ($request->route() && strpos($request->route()->getName(), 'admin.emails') !== false) {
            $this->logEmailActivity($request);
        }
    }

    protected function logEmailActivity(Request $request)
    {
        try {
            $userId = Auth::id();

            EmailLog::create([
                'recipient_type' => $request->input('recipient_type', 'unknown'),
                'subject' => $request->input('subject', 'No subject'),
                'recipient_count' => count($request->input('recipients', [])),
                'sent_by' => $userId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log email activity: ' . $e->getMessage());
        }
    }
}
