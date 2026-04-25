@extends('layouts.app')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Mes adresses</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3">Mes Adresses</h1>
                <p class="text-muted">Gérez vos adresses de livraison et de facturation</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Add New Address Form -->
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt fs-4 me-2" style="color: #B76E79;"></i>
                            <h5 class="mb-0">Ajouter une nouvelle adresse</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        <form action="{{ route('user.addresses.store') }}" method="POST" id="addAddressForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text" name="full_name"
                                           class="form-control @error('full_name') is-invalid @enderror"
                                           value="{{ old('full_name', Auth::user()->name ?? '') }}"
                                           placeholder="Ex: Jean Dupont" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="text" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="Ex: 0612345678" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Type d'adresse *</label>
                                    <select name="address_type" class="form-select @error('address_type') is-invalid @enderror" required>
                                        <option value="shipping" {{ old('address_type') == 'shipping' ? 'selected' : '' }}>Livraison</option>
                                        <option value="billing" {{ old('address_type') == 'billing' ? 'selected' : '' }}>Facturation</option>
                                        <option value="both" {{ old('address_type') == 'both' ? 'selected' : '' }}>Les deux</option>
                                    </select>
                                    @error('address_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Adresse *</label>
                                    <input type="text" name="address_line_1"
                                           class="form-control @error('address_line_1') is-invalid @enderror"
                                           value="{{ old('address_line_1') }}"
                                           placeholder="Ex: 123 Rue de la Paix" required>
                                    @error('address_line_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Complément d'adresse</label>
                                    <input type="text" name="address_line_2"
                                           class="form-control @error('address_line_2') is-invalid @enderror"
                                           value="{{ old('address_line_2') }}"
                                           placeholder="Ex: Appartement 4B">
                                    @error('address_line_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ville *</label>
                                    <input type="text" name="city"
                                           class="form-control @error('city') is-invalid @enderror"
                                           value="{{ old('city') }}"
                                           placeholder="Ex: Paris" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">État/Région *</label>
                                    <input type="text" name="state"
                                           class="form-control @error('state') is-invalid @enderror"
                                           value="{{ old('state') }}"
                                           placeholder="Ex: Île-de-France" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Code Postal *</label>
                                    <input type="text" name="postal_code"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           value="{{ old('postal_code') }}"
                                           placeholder="Ex: 75001" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pays *</label>
                                    <select name="country" class="form-select @error('country') is-invalid @enderror" required>
                                        <option value="">Sélectionner un pays</option>
                                        <option value="FR" {{ old('country', 'FR') == 'FR' ? 'selected' : '' }}>France</option>
                                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                                        <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="LU" {{ old('country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                        <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>États-Unis</option>
                                        <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>Royaume-Uni</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Point de repère</label>
                                    <input type="text" name="landmark"
                                           class="form-control @error('landmark') is-invalid @enderror"
                                           value="{{ old('landmark') }}"
                                           placeholder="Ex: Près de la gare">
                                    @error('landmark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="is_default" id="is_default" value="1"
                                               {{ old('is_default') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Définir comme adresse par défaut
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter l'adresse
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Existing Addresses -->
                @if(isset($addresses) && $addresses->count() > 0)
                <div class="account-card mt-4">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-address-book fs-4 me-2" style="color: #B76E79;"></i>
                            <h5 class="mb-0">Mes adresses enregistrées</h5>
                            <span class="badge bg-secondary ms-2">{{ $addresses->count() }}</span>
                        </div>
                    </div>
                    <div class="account-card-body">
                        @foreach($addresses as $address)
                            <div class="address-item {{ $address->is_default ? 'default-address' : '' }}">
                                @if($address->is_default)
                                    <span class="default-badge">
                                        <i class="fas fa-star"></i>
                                        Adresse par défaut
                                    </span>
                                @endif

                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">{{ $address->full_name }}</h6>
                                        <p class="mb-1">
                                            {{ $address->address_line_1 }}<br>
                                            @if($address->address_line_2)
                                                {{ $address->address_line_2 }}<br>
                                            @endif
                                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                            {{ $address->country }}
                                            @if($address->landmark)
                                                <br><small class="text-muted">Repère: {{ $address->landmark }}</small>
                                            @endif
                                        </p>
                                        <p class="mb-0">
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                            </small>
                                        </p>
                                        <p class="mt-2 mb-0">
                                            <span class="badge bg-light text-dark">
                                                @if($address->address_type == 'shipping')
                                                    <i class="fas fa-truck me-1"></i>Livraison
                                                @elseif($address->address_type == 'billing')
                                                    <i class="fas fa-file-invoice me-1"></i>Facturation
                                                @else
                                                    <i class="fas fa-check-double me-1"></i>Livraison & Facturation
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="address-actions">
                                        @if(!$address->is_default)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success mb-2 w-100"
                                                    onclick="setDefaultAddress({{ $address->id }})">
                                                <i class="fas fa-check me-1"></i>Définir par défaut
                                            </button>
                                        @endif
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary mb-2 w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAddressModal{{ $address->id }}">
                                            <i class="fas fa-edit me-1"></i>Modifier
                                        </button>
                                        <form action="{{ route('user.addresses.destroy', $address) }}"
                                              method="POST"
                                              onsubmit="return confirmDelete(event, this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash me-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit me-2" style="color: #B76E79;"></i>
                                                Modifier l'adresse
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('user.addresses.update', $address) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label class="form-label">Nom complet *</label>
                                                        <input type="text" name="full_name" class="form-control"
                                                               value="{{ old('full_name', $address->full_name) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Téléphone *</label>
                                                        <input type="text" name="phone" class="form-control"
                                                               value="{{ old('phone', $address->phone) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Type d'adresse *</label>
                                                        <select name="address_type" class="form-select" required>
                                                            <option value="shipping" {{ $address->address_type == 'shipping' ? 'selected' : '' }}>Livraison</option>
                                                            <option value="billing" {{ $address->address_type == 'billing' ? 'selected' : '' }}>Facturation</option>
                                                            <option value="both" {{ $address->address_type == 'both' ? 'selected' : '' }}>Les deux</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Adresse *</label>
                                                        <input type="text" name="address_line_1" class="form-control"
                                                               value="{{ old('address_line_1', $address->address_line_1) }}" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Complément d'adresse</label>
                                                        <input type="text" name="address_line_2" class="form-control"
                                                               value="{{ old('address_line_2', $address->address_line_2) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Ville *</label>
                                                        <input type="text" name="city" class="form-control"
                                                               value="{{ old('city', $address->city) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">État/Région *</label>
                                                        <input type="text" name="state" class="form-control"
                                                               value="{{ old('state', $address->state) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Code Postal *</label>
                                                        <input type="text" name="postal_code" class="form-control"
                                                               value="{{ old('postal_code', $address->postal_code) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Pays *</label>
                                                        <select name="country" class="form-select" required>
                                                            <option value="FR" {{ $address->country == 'FR' ? 'selected' : '' }}>France</option>
                                                            <option value="BE" {{ $address->country == 'BE' ? 'selected' : '' }}>Belgique</option>
                                                            <option value="CH" {{ $address->country == 'CH' ? 'selected' : '' }}>Suisse</option>
                                                            <option value="CA" {{ $address->country == 'CA' ? 'selected' : '' }}>Canada</option>
                                                            <option value="LU" {{ $address->country == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Point de repère</label>
                                                        <input type="text" name="landmark" class="form-control"
                                                               value="{{ old('landmark', $address->landmark) }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="is_default"
                                                                   id="is_default_edit_{{ $address->id }}"
                                                                   value="1"
                                                                   {{ $address->is_default ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_default_edit_{{ $address->id }}">
                                                                Définir comme adresse par défaut
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Annuler
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Sauvegarder
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

<style>
/* ===== ADDRESSES PAGE STYLES ===== */
.account-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(44, 62, 80, 0.06);
    border: 1px solid rgba(44, 62, 80, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 24px;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(44, 62, 80, 0.1);
}

.account-card-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #F9FAFB, #FFFFFF);
    border-bottom: 1px solid #F3F4F6;
}

.account-card-header h5 {
    margin: 0;
    color: #1F2937;
    font-weight: 600;
}

.account-card-body {
    padding: 1.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0.5rem;
}

.breadcrumb-item a {
    color: #B76E79;
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item.active {
    color: #6B7280;
}

.address-item {
    position: relative;
    padding: 1.5rem;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.address-item:hover {
    border-color: rgba(183, 110, 121, 0.3);
    box-shadow: 0 4px 12px rgba(183, 110, 121, 0.1);
    transform: translateX(4px);
}

.default-address {
    border: 2px solid #B76E79;
    background: linear-gradient(to right, rgba(183, 110, 121, 0.02), white);
}

.default-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #B76E79, #A85C67);
    color: white;
    padding: 0.35rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.default-badge i {
    font-size: 0.75rem;
}

.address-actions {
    min-width: 140px;
    margin-left: 1.5rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #F9FAFB;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.75rem;
    background: #F3F4F6;
    color: #374151;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #4B5563;
    margin-bottom: 0.375rem;
}

.form-control, .form-select {
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    padding: 0.625rem 1rem;
    transition: all 0.2s ease;
    font-size: 0.9375rem;
}

.form-control:focus, .form-select:focus {
    border-color: #B76E79;
    box-shadow: 0 0 0 3px rgba(183, 110, 121, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #B76E79, #A85C67);
    border: none;
    border-radius: 10px;
    padding: 0.625rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #A85C67, #B76E79);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(183, 110, 121, 0.3);
}

.btn-outline-primary {
    border: 1px solid rgba(183, 110, 121, 0.3);
    color: #B76E79;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: #B76E79;
    border-color: #B76E79;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-success {
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-success:hover {
    background: #28a745;
    border-color: #28a745;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-danger {
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #dc3545;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
    transform: translateY(-1px);
}

.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-header {
    background: linear-gradient(135deg, #F9FAFB, #FFFFFF);
    border-bottom: 1px solid #F3F4F6;
    padding: 1.25rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #F3F4F6;
    padding: 1.25rem 1.5rem;
}

@media (max-width: 768px) {
    .address-item .d-flex {
        flex-direction: column;
    }

    .address-actions {
        margin-left: 0;
        margin-top: 1rem;
        width: 100%;
        display: flex;
        gap: 8px;
    }

    .address-actions .btn {
        flex: 1;
    }

    .default-badge {
        position: static;
        display: inline-flex;
        margin-bottom: 0.75rem;
    }
}
</style>

<script>
function confirmDelete(e, form) {
    e.preventDefault();

    if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ? Cette action est irréversible.')) {
        form.submit();
    }

    return false;
}

function setDefaultAddress(addressId) {
    fetch(`/user/addresses/${addressId}/set-default`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de la mise à jour');
    });
}
</script>
@endsection
