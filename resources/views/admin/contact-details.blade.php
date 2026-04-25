@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Détails du message</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Tableau de bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.contacts') }}">
                        <div class="text-tiny">Messages</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Message #{{ $contact->id }}</div>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Message Details Column -->
            <div class="col-lg-8">
                <div class="wg-box">
                    <div class="flex items-center justify-between mb-4">
                        <h5>Contenu du message</h5>
                        <div class="flex gap-10">
                            <a href="{{ route('admin.contacts') }}" class="tf-button style-3">
                                <i class="icon-arrow-left"></i> Retour
                            </a>
                            @if(!$contact->read)
                            <a href="{{ route('admin.contact.mark-read', $contact->id) }}" class="tf-button style-1">
                                <i class="icon-check"></i> Marquer comme lu
                            </a>
                            @endif
                            <form action="{{ route('admin.contact.delete', $contact->id) }}" method="POST" class="d-inline" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tf-button style-5" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                    <i class="icon-trash-2"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="message-content p-4" style="background: #F9FAFB; border-radius: 12px;">
                        <p class="mb-4" style="line-height: 1.8; font-size: 15px;">{{ nl2br(e($contact->comment)) }}</p>
                    </div>

                    <!-- Reply Section (Optional) -->
                    <div class="mt-4">
                        <h5 class="mb-3">Répondre</h5>
                        <form action="{{ route('admin.contact.reply', $contact->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="reply" class="form-control" rows="4" placeholder="Écrivez votre réponse..."></textarea>
                            </div>
                            <button type="submit" class="tf-button style-1">
                                <i class="icon-send"></i> Envoyer la réponse
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info Column -->
            <div class="col-lg-4">
                <!-- Sender Info Card -->
                <div class="wg-box mb-4">
                    <h5 class="mb-3">Expéditeur</h5>
                    <div class="sender-info">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 20px;">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-1" style="font-size: 16px;">{{ $contact->name }}</h6>
                                @if($contact->user)
                                <span class="badge bg-success">Client inscrit</span>
                                @else
                                <span class="badge bg-secondary">Visiteur</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="icon-mail me-3" style="color: #667eea; width: 20px;"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </div>
                        </div>

                        @if($contact->phone)
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="icon-phone me-3" style="color: #667eea; width: 20px;"></i>
                            <div>
                                <small class="text-muted d-block">Téléphone</small>
                                <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Message Info Card -->
                <div class="wg-box mb-4">
                    <h5 class="mb-3">Informations</h5>
                    <div class="info-list">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Statut:</span>
                            @if($contact->read)
                                <span class="badge bg-success"><i class="icon-check"></i> Lu</span>
                            @else
                                <span class="badge bg-warning"><i class="icon-eye-off"></i> Non lu</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Reçu le:</span>
                            <span class="fw-500">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">IP:</span>
                            <span class="fw-500">{{ $contact->ip_address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- User Info Card (if user exists) -->
                @if($contact->user)
                <div class="wg-box">
                    <h5 class="mb-3">Client associé</h5>
                    <div class="user-info">
                        <div class="d-flex align-items-center mb-3">
                            @if($contact->user->avatar)
                                <img src="{{ asset('uploads/avatars/'.$contact->user->avatar) }}"
                                     alt="{{ $contact->user->name }}"
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                            @else
                                <div class="avatar me-3" style="width: 50px; height: 50px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($contact->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $contact->user->name }}</h6>
                                <small class="text-muted">Inscrit le {{ $contact->user->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Commandes:</span>
                            <span class="fw-500">{{ $contact->user->orders->count() }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total dépensé:</span>
                            <span class="fw-500">{{ number_format($contact->user->orders->sum('total'), 2) }} €</span>
                        </div>

                        <a href="{{ route('admin.user.view', $contact->user->id) }}" class="btn btn-primary w-100 mt-3">
                            <i class="icon-user"></i> Voir le profil client
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .wg-box {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .message-content {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 500;
    }

    .tf-button {
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .tf-button i {
        margin-right: 8px;
    }

    .info-item {
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #F9FAFB;
    }
</style>
@endsection
