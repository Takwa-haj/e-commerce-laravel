@extends('layouts.app')

@section('content')
<main class="min-vh-100" style="background: var(--surface); padding: 40px 0;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.messages') }}">Mes messages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Détails du message #{{ $message->id }}</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3">Détails du message</h1>
            </div>
        </div>

        <div class="row">
            <!-- Side Navigation -->
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <!-- Message Details Content -->
            <div class="col-lg-9">
                <div class="message-details-container">
                    <!-- Header with back button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route('user.messages') }}" class="btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            Retour aux messages
                        </a>
                    </div>

                    <!-- Message Status -->
                    <div class="status-bar mb-4">
                        @if($message->read)
                            <span class="status-badge read">
                                <i class="fas fa-check-circle"></i> Message traité
                            </span>
                        @else
                            <span class="status-badge pending">
                                <i class="fas fa-clock"></i> En attente de réponse
                            </span>
                        @endif
                        <span class="message-id">Message #{{ $message->id }}</span>
                    </div>

                    <!-- Message Card -->
                    <div class="message-card">
                        <div class="message-header">
                            <div class="sender-info">
                                <div class="sender-avatar">
                                    {{ strtoupper(substr($message->name, 0, 1)) }}
                                </div>
                                <div class="sender-details">
                                    <h4>{{ $message->name }}</h4>
                                    <div class="sender-contact">
                                        <span><i class="fas fa-envelope"></i> {{ $message->email }}</span>
                                        @if($message->phone)
                                            <span><i class="fas fa-phone"></i> {{ $message->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="message-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ $message->created_at->format('d F Y') }}
                                <br>
                                <small>{{ $message->created_at->format('H:i') }}</small>
                            </div>
                        </div>

                        <div class="message-content">
                            <h5 class="content-title">Message :</h5>
                            <div class="content-body">
                                {{ nl2br(e($message->comment)) }}
                            </div>
                        </div>

                        <!-- Admin Reply Section (if exists) -->
                        @if($message->admin_reply)
                        <div class="admin-reply">
                            <div class="reply-header">
                                <i class="fas fa-reply"></i>
                                <h5>Réponse de notre équipe</h5>
                                <span class="reply-date">{{ $message->replied_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="reply-body">
                                {{ nl2br(e($message->admin_reply)) }}
                            </div>
                        </div>
                        @endif

                        <!-- Message Info Footer -->
                        <div class="message-footer">
                            <div class="info-item">
                                <i class="fas fa-map-pin"></i>
                                <span>IP: {{ $message->ip_address ?? 'Non enregistrée' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-globe"></i>
                                <span>Appareil: {{ $message->user_agent ? Str::limit($message->user_agent, 50) : 'Non enregistré' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons mt-4">
                        <a href="{{ route('home.contact') }}" class="btn-new-message">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Nouveau message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .message-details-container {
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--bg_soft);
        color: var(--text_secondary);
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: var(--border);
        color: var(--text_primary);
    }

    .status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: var(--bg_soft);
        border-radius: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 14px;
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

    .message-id {
        color: var(--text_secondary);
        font-size: 14px;
        font-weight: 500;
    }

    .message-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px;
        background: var(--bg_soft);
        border-bottom: 1px solid var(--border);
    }

    .sender-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 20px;
    }

    .sender-details h4 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 5px;
    }

    .sender-contact span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: var(--text_secondary);
    }

    .sender-contact i {
        color: var(--primary);
        font-size: 12px;
    }

    .message-date {
        text-align: right;
        color: var(--text_secondary);
        font-size: 14px;
    }

    .message-date small {
        font-size: 12px;
        color: var(--text_secondary);
    }

    .message-content {
        padding: 30px;
    }

    .content-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 15px;
    }

    .content-body {
        background: var(--bg_soft);
        padding: 20px;
        border-radius: 12px;
        line-height: 1.8;
        color: var(--text_secondary);
    }

    .admin-reply {
        margin: 20px 30px 30px;
        background: color-mix(in srgb, var(--info) 10%, transparent);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid var(--primary);
    }

    .reply-header i {
        color: var(--primary);
    }

    .reply-header h5 {
        font-size: 15px;
        font-weight: 600;
        color: var(--text_primary);
    }

    .reply-date {
        margin-left: auto;
        font-size: 12px;
        color: var(--text_secondary);
    }

    .reply-body {
        line-height: 1.8;
        color: var(--text_secondary);
    }

    .message-footer {
        display: flex;
        gap: 20px;
        padding: 15px 20px;
        background: var(--bg_soft);
        border-top: 1px solid var(--border);
        font-size: 12px;
        color: var(--text_secondary);
    }

    .btn-new-message {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-new-message:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
    }
</style>
@endsection
