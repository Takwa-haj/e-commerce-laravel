<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UnifiedAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UnifiedAddressController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the user's addresses.
     */
    public function index()
    {
        $addresses = UnifiedAddress::forUser(Auth::id())
            ->latest()
            ->get();

        return view('user.addresses.index', compact('addresses'));
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'is_default' => 'sometimes|boolean',
        ], [
            'full_name.required' => 'Le nom complet est obligatoire.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'address_line_1.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'state.required' => 'La région/État est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
            'address_type.required' => 'Le type d\'adresse est obligatoire.',
            'address_type.in' => 'Le type d\'adresse doit être livraison, facturation ou les deux.',
        ]);

        try {
            // If setting as default, remove default from other addresses
            if ($request->has('is_default') && $request->boolean('is_default')) {
                UnifiedAddress::forUser(Auth::id())->update(['is_default' => false]);
                $validated['is_default'] = true;
            } else {
                $validated['is_default'] = UnifiedAddress::forUser(Auth::id())->count() === 0;
            }

            $validated['user_id'] = Auth::id();

            UnifiedAddress::create($validated);

            return redirect()->route('user.addresses.index')
                ->with('success', 'Adresse ajoutée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout de l\'adresse. Veuillez réessayer.');
        }
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, UnifiedAddress $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
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
            'is_default' => 'sometimes|boolean',
        ], [
            'full_name.required' => 'Le nom complet est obligatoire.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'address_line_1.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'state.required' => 'La région/État est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
            'address_type.required' => 'Le type d\'adresse est obligatoire.',
            'address_type.in' => 'Le type d\'adresse doit être livraison, facturation ou les deux.',
        ]);

        try {
            // If setting as default, remove default from other addresses
            if ($request->has('is_default') && $request->boolean('is_default')) {
                UnifiedAddress::forUser(Auth::id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
                $validated['is_default'] = true;
            }

            $address->update($validated);

            return redirect()->route('user.addresses.index')
                ->with('success', 'Adresse mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'adresse.');
        }
    }

    /**
     * Remove the specified address.
     */
    public function destroy(UnifiedAddress $address)
    {
        $this->authorize('delete', $address);

        try {
            $wasDefault = $address->is_default;
            $address->delete();

            // If we deleted the default address, set another one as default
            if ($wasDefault) {
                $newDefault = UnifiedAddress::forUser(Auth::id())->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            return redirect()->route('user.addresses.index')
                ->with('success', 'Adresse supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'adresse.');
        }
    }

    /**
     * Set the specified address as default.
     */
    public function setDefault(UnifiedAddress $address)
    {
        $this->authorize('update', $address);

        try {
            UnifiedAddress::forUser(Auth::id())->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Adresse par défaut mise à jour'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue'
            ], 500);
        }
    }

    /**
     * Get addresses for checkout (AJAX).
     */
    public function getAddressesForCheckout()
    {
        $addresses = UnifiedAddress::forUser(Auth::id())
            ->shipping()
            ->get();

        $defaultAddress = $addresses->where('is_default', true)->first();

        return response()->json([
            'success' => true,
            'addresses' => $addresses,
            'default_address' => $defaultAddress
        ]);
    }
}
