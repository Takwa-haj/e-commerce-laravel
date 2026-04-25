@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Envoyer un email</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="emailTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="single-tab" data-toggle="tab" href="#single" role="tab">
                                Email individuel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab">
                                Email groupé
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Single Email Tab -->
                        <div class="tab-pane fade show active" id="single" role="tabpanel">
                            <form action="{{ route('admin.email.send') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type de destinataire *</label>
                                            <select class="form-control" name="recipient_type" id="recipientType" required>
                                                <option value="customer">Client (commande récente)</option>
                                                <option value="user">Utilisateur</option>
                                                <option value="manual">Email manuel</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" id="recipientSelectGroup">
                                            <label>Sélectionner le destinataire *</label>
                                            <select class="form-control" name="recipient_id" id="recipientSelect">
                                                <option value="">Sélectionner...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group d-none" id="manualEmailGroup">
                                            <label>Email du destinataire *</label>
                                            <input type="email" class="form-control" name="recipient_email" placeholder="email@exemple.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Commande associée (optionnel)</label>
                                    <select class="form-control" name="order_id">
                                        <option value="">Aucune</option>
                                        @foreach($recentOrders as $order)
                                            <option value="{{ $order->id }}">
                                                #{{ $order->id }} - {{ $order->name }} ({{ $order->total }}€)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Sujet *</label>
                                    <input type="text" class="form-control" name="subject" required>
                                </div>

                                <div class="form-group">
                                    <label>Message *</label>
                                    <textarea class="form-control summernote" name="message" rows="10" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="attach_invoice" value="1">
                                            <label class="form-check-label">Joindre la facture (si commande sélectionnée)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Envoyer l'email
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Bulk Email Tab -->
                        <div class="tab-pane fade" id="bulk" role="tabpanel">
                            <form action="{{ route('admin.email.bulk') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Destinataires *</label>
                                    <select class="form-control" name="recipients" required>
                                        <option value="all_users">Tous les utilisateurs</option>
                                        <option value="subscribed">Abonnés newsletter uniquement</option>
                                        <option value="recent_customers">Clients récents (30 derniers jours)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Sujet *</label>
                                    <input type="text" class="form-control" name="subject" required>
                                </div>

                                <div class="form-group">
                                    <label>Message *</label>
                                    <textarea class="form-control summernote" name="message" rows="10" required></textarea>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" name="test_mode" value="1" checked>
                                    <label class="form-check-label">Mode test (envoyer seulement à moi-même)</label>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Attention :</strong> L'envoi groupé peut prendre du temps. Utilisez le mode test d'abord.
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-bullhorn"></i> Envoyer en masse
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle recipient type change
    $('#recipientType').change(function() {
        if ($(this).val() === 'manual') {
            $('#recipientSelectGroup').addClass('d-none');
            $('#manualEmailGroup').removeClass('d-none');
        } else {
            $('#recipientSelectGroup').removeClass('d-none');
            $('#manualEmailGroup').addClass('d-none');
        }
    });

    // Initialize Summernote editor
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush
@endsection
