@extends('layouts.app')
@section('content')

<style>
    .text-success {
        color: var(--success);
    }
    .text-danger {
        color: var(--error);
    }

    /* Cart Item Actions */
    .cart-item-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border_soft);
        background: var(--surface);
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--text_main);
    }

    .qty-btn:hover {
        background: var(--bg_soft);
        border-color: var(--primary);
        color: var(--primary);
    }

    .qty-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .qty-value {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        color: var(--text_main);
    }

    .remove-btn {
        color: var(--error);
        background: transparent;
        border: none;
        padding: 8px;
        border-radius: 4px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .remove-btn:hover {
        background: var(--error_bg);
    }

    /* Cart Table */
    .cart-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px var(--card_shadow);
        background: var(--bg_main);
    }

    .cart-table th {
        background: var(--bg_soft);
        padding: 16px;
        font-weight: 600;
        border-bottom: 2px solid var(--border_soft);
        color: var(--text_main);
        letter-spacing: 0.5px;
    }

    .cart-table td {
        padding: 20px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border_soft);
        background: var(--bg_light);
    }

    .cart-table tr:hover td {
        background: color-mix(in srgb, var(--primary) 8%, transparent);
    }

    /* Cart Totals */
    .cart-totals {
        background: var(--bg_light);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px var(--card_shadow);
        border: 1px solid var(--border_soft);
    }

    .cart-totals th {
        font-weight: 500;
        padding: 12px 0;
        color: var(--text_secondary);
    }

    .cart-totals td {
        text-align: right;
        padding: 12px 0;
        font-weight: 600;
        color: var(--text_main);
    }

    .cart-totals tr:last-child {
        border-top: 2px solid var(--divider);
    }

    .cart-totals tr:last-child td {
        color: var(--primary);
        font-size: 1.1em;
    }

    /* Coupon Form */
    .coupon-form {
        background: var(--bg_light);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid var(--border_soft);
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .coupon-form input[type="text"] {
        border: 1px solid var(--primary);
        background: var(--surface);
        color: var(--text_main);
        border-radius: 6px;
        padding: 12px 16px;
        flex-grow: 1;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .coupon-form input[type="text"]:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .coupon-form input[type="text"]::placeholder {
        color: var(--text_secondary);
    }

    .coupon-form input[type="submit"] {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .coupon-form input[type="submit"]:hover {
        filter: brightness(1.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 20%, transparent);
    }

    /* Checkout Steps */
    .checkout-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .checkout-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: var(--border_soft);
        z-index: 1;
    }

    .checkout-steps__item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 2;
        text-decoration: none;
        color: var(--text_secondary);
        flex: 1;
    }

    .checkout-steps__item.active {
        color: var(--primary);
    }

    .checkout-steps__item-number {
        width: 40px;
        height: 40px;
        background: var(--surface);
        border: 2px solid var(--border_soft);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .checkout-steps__item.active .checkout-steps__item-number {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .checkout-steps__item-title {
        display: flex;
        flex-direction: column;
        font-size: 14px;
    }

    .checkout-steps__item-title em {
        font-style: normal;
        font-size: 12px;
        color: var(--text_secondary);
        margin-top: 2px;
    }

    /* Checkout Button */
    .btn-checkout {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        border: none;
        padding: 16px 32px;
        font-weight: 600;
        border-radius: 8px;
        width: 100%;
        transition: all 0.3s ease;
        color: white;
        cursor: pointer;
    }

    .btn-checkout:hover {
        filter: brightness(1.05);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px color-mix(in srgb, var(--primary) 30%, transparent);
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-cart-icon {
        font-size: 64px;
        color: var(--text_secondary);
        margin-bottom: 20px;
    }

    .cart-table thead tr th:first-child {
        border-radius: 12px 0 0 0;
    }

    .cart-table thead tr th:last-child {
        border-radius: 0 12px 0 0;
    }

    .cart-totals tr:last-child td {
        color: var(--primary);
    }

    /* Address Suggestions */
    .address-suggestions-container {
        background: var(--bg_light);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--border_soft);
    }

    .address-suggestions-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--text_main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .address-suggestions-title i {
        color: var(--primary);
    }

    .address-suggestions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .address-suggestion-card {
        background: var(--surface);
        border: 2px solid var(--border_soft);
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .address-suggestion-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .address-suggestion-card.selected {
        border-color: var(--primary);
        background: var(--bg_soft);
    }

    .address-suggestion-card .address-name {
        font-weight: 600;
        color: var(--text_main);
        font-size: 15px;
    }

    .address-suggestion-card .address-type {
        font-size: 12px;
        color: var(--text_secondary);
        text-transform: capitalize;
    }

    .address-suggestion-card .address-details {
        font-size: 13px;
        line-height: 1.5;
        color: var(--text_secondary);
    }

    .address-suggestion-card .address-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--primary);
        color: white;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .use-address-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .use-address-btn:hover {
        filter: brightness(0.9);
    }

    .toggle-address-form {
        color: var(--primary);
        background: none;
        border: none;
        padding: 0;
        font-size: 14px;
        cursor: pointer;
        text-decoration: underline;
    }

    .toggle-address-form:hover {
        filter: brightness(0.9);
    }

    .address-form-container {
        display: none;
        background: var(--surface);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid var(--border_soft);
    }

    .address-form-container.show {
        display: block;
    }

    /* Form Styles */
    .form-floating input, .form-floating textarea, .form-floating select {
        border: 1px solid var(--border_soft);
        border-radius: 6px;
        padding: 12px;
        background: var(--surface);
        color: var(--text_main);
    }

    .form-floating input:focus, .form-floating textarea:focus, .form-floating select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 8%, transparent);
        outline: none;
    }

    .form-floating label {
        color: var(--text_secondary);
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        filter: brightness(0.9);
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Livraison et Paiement</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Panier</span>
            <em>Gérez vos articles</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Livraison et Paiement</span>
            <em>Validez vos articles</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Vérifiez et soumettez votre commande</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
        @csrf

        <!-- Address Suggestions Section -->
        @if($savedAddresses->count() > 0)
        <div class="address-suggestions-container">
            <div class="address-suggestions-title">
                <i class="fas fa-map-marker-alt"></i>
                Adresses enregistrées dans votre compte
            </div>

            <div class="address-suggestions-grid" id="addressSuggestionsGrid">
                @foreach($savedAddresses as $address)
                <div class="address-suggestion-card @if($address->is_default) selected @endif"
                     data-address-id="{{ $address->id }}"
                     data-full-name="{{ $address->full_name }}"
                     data-phone="{{ $address->phone }}"
                     data-address-line-1="{{ $address->address_line_1 }}"
                     data-address-line-2="{{ $address->address_line_2 }}"
                     data-city="{{ $address->city }}"
                     data-state="{{ $address->state }}"
                     data-postal-code="{{ $address->postal_code }}"
                     data-country="{{ $address->country }}"
                     data-landmark="{{ $address->landmark }}">

                    @if($address->is_default)
                        <div class="address-badge">Adresse par défaut</div>
                    @endif

                    <div class="address-header">
                        <div>
                            <div class="address-name">{{ $address->full_name }}</div>
                            <div class="address-type">
                                @if($address->address_type == 'shipping')
                                    Livraison
                                @elseif($address->address_type == 'billing')
                                    Facturation
                                @else
                                    Les deux
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="address-details">
                        {{ $address->address_line_1 }}<br>
                        @if($address->address_line_2)
                            {{ $address->address_line_2 }}<br>
                        @endif
                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                        {{ $address->country }}
                        @if($address->landmark)
                            <br><small>Repère: {{ $address->landmark }}</small>
                        @endif
                    </div>

                    <div class="address-phone">
                        <i class="fas fa-phone"></i> {{ $address->phone }}
                    </div>

                    <button type="button" class="use-address-btn mt-3" onclick="useSavedAddress({{ $address->id }})">
                        <i class="fas fa-check"></i> Utiliser cette adresse
                    </button>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="toggle-address-form" onclick="toggleAddressForm()">
                    <i class="fas fa-plus"></i> Ajouter une nouvelle adresse
                </button>
                <div id="selectedAddressInfo" class="text-muted small" style="display: {{ $defaultAddress ? 'block' : 'none' }};">
                    <i class="fas fa-check text-success"></i> Adresse sélectionnée
                </div>
            </div>
        </div>
        @endif

        <!-- Billing Details -->
        <div class="billing-info__wrapper">
            <div class="row">
                <div class="col-6">
                    <h4>DÉTAILS DE LIVRAISON</h4>
                </div>
                <div class="col-6"></div>
            </div>

            <!-- Address Form Container -->
            <div class="address-form-container @if($savedAddresses->count() == 0) show @endif" id="addressFormContainer">
                <input type="hidden" id="use_saved_address" name="use_saved_address" value="{{ $defaultAddress ? '1' : '0' }}">
                <input type="hidden" id="selected_address_id" name="saved_address_id" value="{{ $defaultAddress->id ?? '' }}">

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="full_name" id="full_name" required
                                   value="{{ old('full_name', $defaultAddress->full_name ?? Auth::user()->name ?? '') }}">
                            <label for="full_name">Nom Complet *</label>
                            @error('full_name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="phone" id="phone" required
                                   value="{{ old('phone', $defaultAddress->phone ?? '') }}">
                            <label for="phone">Numéro de Téléphone *</label>
                            @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="postal_code" id="postal_code" required
                                   value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}">
                            <label for="postal_code">Code Postal *</label>
                            @error('postal_code') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mt-3 mb-3">
                            <input type="text" class="form-control" name="state" id="state" required
                                   value="{{ old('state', $defaultAddress->state ?? '') }}">
                            <label for="state">Région / État *</label>
                            @error('state') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="city" id="city" required
                                   value="{{ old('city', $defaultAddress->city ?? '') }}">
                            <label for="city">Ville *</label>
                            @error('city') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="address_line_1" id="address_line_1" required
                                   value="{{ old('address_line_1', $defaultAddress->address_line_1 ?? '') }}">
                            <label for="address_line_1">Adresse *</label>
                            @error('address_line_1') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="address_line_2" id="address_line_2"
                                   value="{{ old('address_line_2', $defaultAddress->address_line_2 ?? '') }}">
                            <label for="address_line_2">Complément d'adresse</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" name="landmark" id="landmark" required
                                   value="{{ old('landmark', $defaultAddress->landmark ?? '') }}">
                            <label for="landmark">Point de Repère *</label>
                            @error('landmark') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <select name="country" class="form-select" id="country" required>
                                <option value="FR" {{ ($defaultAddress->country ?? 'FR') == 'FR' ? 'selected' : '' }}>France</option>
                                <option value="BE" {{ ($defaultAddress->country ?? '') == 'BE' ? 'selected' : '' }}>Belgique</option>
                                <option value="CH" {{ ($defaultAddress->country ?? '') == 'CH' ? 'selected' : '' }}>Suisse</option>
                                <option value="CA" {{ ($defaultAddress->country ?? '') == 'CA' ? 'selected' : '' }}>Canada</option>
                            </select>
                            <label for="country">Pays</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" name="save_to_account" id="save_to_account" value="1" checked>
                            <label class="form-check-label" for="save_to_account">
                                Sauvegarder cette adresse dans mon compte pour les commandes futures
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Totals -->
        <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Votre Commande</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>PRODUIT</th>
                      <th class="text-right">SOUS-TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach (Cart::instance('cart')->content() as $item )
                    <tr>
                      <td>
                       {{$item->name}} x {{$item->qty}}
                      </td>
                      <td class="text-right">
                       {{$item->subtotal()}}€
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                @if(Session::has('discounts'))
                    <table class="checkout-totals">
                        <tbody>
                            <tr>
                                <th>SOUS-TOTAL</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Cart::instance('cart')->subtotal() }}</td>
                            </tr>
                            <tr>
                                <th>RÉDUCTION ({{ Session::get('coupon')['code'] }})</th>
                                <td class="text-right">-{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['discount'] }}</td>
                            </tr>
                            <tr>
                                <th>SOUS-TOTAL APRÈS RÉDUCTION</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['subtotal'] }}</td>
                            </tr>
                            <tr>
                                <th>LIVRAISON</th>
                                <td class="text-right">
                                    @if((Session::get('discounts')['shipping'] ?? 0) > 0)
                                        {{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['shipping'] }}
                                    @else
                                        Livraison gratuite
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>TVA ({{ $settings->tax_rate ?? config('cart.tax') }}%)</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['tax'] }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['total'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="checkout-totals">
                        <tbody>
                            <tr>
                                <th>SOUS-TOTAL</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Cart::instance('cart')->subtotal() }}</td>
                            </tr>
                            <tr>
                                <th>LIVRAISON</th>
                                <td class="text-right">
                                    @if(($settings->shipping_cost ?? 0) > 0)
                                        {{ $settings->currency_symbol ?? '€' }}{{ $settings->shipping_cost }}
                                    @else
                                        Livraison gratuite
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>TVA ({{ $settings->tax_rate ?? config('cart.tax') }}%)</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Cart::instance('cart')->tax() }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <td class="text-right">{{ $settings->currency_symbol ?? '€' }}{{ Cart::instance('cart')->total() }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
              </div>
              <div class="checkout__payment-methods">
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode"  id="mode1" value="card">
                  <label class="form-check-label" for="mode1">
                    CARTE DE DÉBIT OU CRÉDIT
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode"  id="mode3" value="cod">
                  <label class="form-check-label" for="mode3">
                    Paiement à la livraison
                  </label>
                </div>

                <div class="policy-text">
                  Vos données personnelles seront utilisées pour traiter votre commande, accompagner votre expérience sur ce site web et pour d'autres finalités décrites dans notre <a href="terms.html" target="_blank">politique de confidentialité</a>.
                </div>
              </div>
              <button class="btn btn-primary btn-checkout">PASSER LA COMMANDE</button>
            </div>
          </div>
        </div>
      </form>
    </section>
</main>

<script>
// Auto-select default address on page load
document.addEventListener('DOMContentLoaded', function() {
    const defaultCard = document.querySelector('.address-suggestion-card.selected');
    if (defaultCard) {
        useSavedAddress(defaultCard.dataset.addressId);
    }
});

function useSavedAddress(addressId) {
    const addressCard = document.querySelector(`.address-suggestion-card[data-address-id="${addressId}"]`);
    if (!addressCard) return;

    // Extract address details from data attributes
    const fullName = addressCard.dataset.fullName;
    const phone = addressCard.dataset.phone;
    const addressLine1 = addressCard.dataset.addressLine1;
    const addressLine2 = addressCard.dataset.addressLine2 || '';
    const city = addressCard.dataset.city;
    const state = addressCard.dataset.state;
    const postalCode = addressCard.dataset.postalCode;
    const country = addressCard.dataset.country || 'FR';
    const landmark = addressCard.dataset.landmark || '';

    // Set the hidden fields
    document.getElementById('use_saved_address').value = '1';
    document.getElementById('selected_address_id').value = addressId;

    // Populate the form fields
    document.getElementById('full_name').value = fullName;
    document.getElementById('phone').value = phone;
    document.getElementById('postal_code').value = postalCode;
    document.getElementById('state').value = state;
    document.getElementById('city').value = city;
    document.getElementById('address_line_1').value = addressLine1;
    document.getElementById('address_line_2').value = addressLine2;
    document.getElementById('landmark').value = landmark;
    document.getElementById('country').value = country;

    // Hide the address form
    document.getElementById('addressFormContainer').style.display = 'none';

    // Show selected address info
    document.getElementById('selectedAddressInfo').style.display = 'block';

    // Visual selection
    document.querySelectorAll('.address-suggestion-card').forEach(card => {
        card.classList.remove('selected');
    });
    addressCard.classList.add('selected');

    showToast('Adresse sélectionnée avec succès!', 'success');
}

function toggleAddressForm() {
    const formContainer = document.getElementById('addressFormContainer');
    const isVisible = formContainer.style.display !== 'none';

    if (isVisible) {
        formContainer.style.display = 'none';
        // Reset saved address selection
        document.getElementById('use_saved_address').value = '0';
        document.getElementById('selected_address_id').value = '';
        document.getElementById('selectedAddressInfo').style.display = 'none';

        // Remove selected class from cards
        document.querySelectorAll('.address-suggestion-card').forEach(card => {
            card.classList.remove('selected');
        });
    } else {
        formContainer.style.display = 'block';
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast-alert';
    toast.innerHTML = `
        <div style="display:flex;align-items:center;gap:10px;padding:12px 20px;
            background:${type === 'success' ? '#CDE7D8' : '#F8D7DA'};
            color:${type === 'success' ? '#2F6F4E' : '#842029'};
            border-left:4px solid ${type === 'success' ? '#2F6F4E' : '#842029'};
            border-radius:5px;position:fixed;top:20px;right:20px;z-index:9999;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Form submission handling
document.querySelector('form[name="checkout-form"]').addEventListener('submit', function(e) {
    const useSavedAddress = document.getElementById('use_saved_address').value === '1';
    const addressId = document.getElementById('selected_address_id').value;

    if (useSavedAddress && !addressId) {
        e.preventDefault();
        showToast('Veuillez sélectionner une adresse', 'error');
        return false;
    }
});
</script>
@endsection
