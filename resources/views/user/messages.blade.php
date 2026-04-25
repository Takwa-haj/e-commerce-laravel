@extends('layouts.app')

@section('content')
<main class="min-vh-100" style="background: var(--surface); padding: 40px 0;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mes messages</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3">Mes messages</h1>
                <p class="text-muted">Historique de vos conversations avec notre équipe</p>
            </div>
        </div>

        <div class="row">
            <!-- Side Navigation -->
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <!-- Messages Content -->
            <div class="col-lg-9">
                <div class="messages-container">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Messages List -->
                    @if($messages->count() > 0)
                        <div class="messages-list">
                            @foreach($messages as $message)
                                <div class="message-card {{ !$message->read ? 'unread' : '' }} mb-3">
                                    <div class="message-card-header">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="message-icon">
                                                @if(!$message->read)
                                                    <span class="unread-dot"></span>
                                                @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--primary)" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                                </svg>
                                            </div>
                                            <div class="message-info flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h5 class="message-subject mb-0">
                                                        Message #{{ $message->id }}
                                                        @if(!$message->read)
                                                            <span class="badge bg-warning ms-2">Non lu</span>
                                                        @endif
                                                    </h5>
                                                    <span class="message-date">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <p class="message-preview mb-2">{{ Str::limit($message->comment, 100) }}</p>
                                                <div class="message-status">
                                                    @if($message->read)
                                                        <span class="status-badge read">
                                                            <i class="fas fa-check-circle"></i> Traité
                                                        </span>
                                                    @else
                                                        <span class="status-badge pending">
                                                            <i class="fas fa-clock"></i> En attente
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-card-footer">
                                        <a href="{{ route('user.message.details', $message->id) }}" class="btn-view">
                                            Voir les détails
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state text-center py-5">
                            <div class="empty-state-icon mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="var(--text-muted)" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                </svg>
                            </div>
                            <h4 class="fw-bold mb-3" style="color: var(--text-main);">Aucun message pour le moment</h4>
                            <p class="mb-4" style="color: var(--text-secondary);">Vous n'avez pas encore envoyé de message. Utilisez notre formulaire de contact pour nous écrire.</p>
                            <a href="{{ route('home.contact') }}" class="btn btn-primary">
                                Contactez-nous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .messages-container {
        background: var(--surface);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .message-card {
        background: var(--bg_soft);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px var(--card_shadow);
        border-color: var(--primary);
    }

    .message-card.unread {
        background: color-mix(in srgb, var(--info) 8%, transparent);
        border-left: 4px solid var(--primary);
    }

    .message-icon {
        position: relative;
        width: 40px;
        height: 40px;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .unread-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 10px;
        height: 10px;
        background: var(--error);
        border-radius: 50%;
        border: 2px solid var(--surface);
    }

    .message-subject {
        font-size: 16px;
        font-weight: 600;
        color: var(--text_primary);
    }

    .message-date {
        font-size: 13px;
        color: var(--text_secondary);
    }

    .message-preview {
        font-size: 14px;
        color: var(--text_secondary);
        line-height: 1.6;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.read {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .status-badge.pending {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        gap: 12px;
        color: var(--primary);
    }

    .message-card-footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }

    .empty-state {
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: var(--bg_soft);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
    }

    .badge.bg-warning {
        background: color-mix(in srgb, var(--warning) 15%, transparent) !important;
        color: var(--warning);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
    }
</style>
@endsection
