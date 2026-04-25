<?php
// app/Http/Controllers/User/AddressController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UnifiedAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = UnifiedAddress::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'address_type' => 'required|in:shipping,billing,both',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->boolean('is_default')) {
            UnifiedAddress::where('user_id', Auth::id())
                ->update(['is_default' => false]);
        }

        // Make first address default automatically
        $isFirst = UnifiedAddress::where('user_id', Auth::id())->count() === 0;

        UnifiedAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'landmark' => $request->landmark,
            'address_type' => $request->address_type,
            'is_default' => $request->boolean('is_default') || $isFirst,
        ]);

        return redirect()->route('user.addresses')
            ->with('success', 'Adresse ajoutée avec succès.');
    }

    public function update(Request $request, $id)
    {
        $address = UnifiedAddress::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'address_type' => 'required|in:shipping,billing,both',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->boolean('is_default')) {
            UnifiedAddress::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'landmark' => $request->landmark,
            'address_type' => $request->address_type,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('user.addresses')
            ->with('success', 'Adresse mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $address = UnifiedAddress::where('user_id', Auth::id())->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // If deleted address was default, set another as default
        if ($wasDefault) {
            $newDefault = UnifiedAddress::where('user_id', Auth::id())->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return redirect()->route('user.addresses')
            ->with('success', 'Adresse supprimée avec succès.');
    }

    public function setDefault($id)
    {
        $address = UnifiedAddress::where('user_id', Auth::id())->findOrFail($id);

        UnifiedAddress::where('user_id', Auth::id())
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse par défaut mise à jour'
        ]);
    }
}
