@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Envoyer des emails aux utilisateurs</h1>
                <div>
                    <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <button class="btn btn-outline-primary" onclick="testEmailConfiguration()">
                        <i class="fas fa-paper-plane me-1"></i> Tester la configuration
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Utilisateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsers">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Emails envoyés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="sentEmails">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Taux de succès</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="successRate">0%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Emails ce mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="monthEmails">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column: User Selection -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Sélection des utilisateurs
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Search Users -->
                    <div class="mb-4">
                        <label class="form-label">Rechercher un utilisateur</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="userSearch"
                                   placeholder="Nom ou email...">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchUsers()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="mb-4">
                        <label class="form-label">Filtrer par</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="filterStatus">
                                    <option value="">Tous les statuts</option>
                                    <option value="active">Actifs</option>
                                    <option value="inactive">Inactifs</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="filterRole">
                                    <option value="">Tous les rôles</option>
                                    <option value="USR">Utilisateurs</option>
                                    <option value="ADM">Administrateurs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Selection -->
                    <div class="mb-4">
                        <label class="form-label">Sélection rapide</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllUsers()">
                                <i class="fas fa-check-square me-1"></i> Tous les utilisateurs
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectActiveUsers()">
                                <i class="fas fa-user-check me-1"></i> Utilisateurs actifs
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="selectAdmins()">
                                <i class="fas fa-user-shield me-1"></i> Administrateurs
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="deselectAll()">
                                <i class="fas fa-times-circle me-1"></i> Tout désélectionner
                            </button>
                        </div>
                    </div>

                    <!-- Selected Users -->
                    <div>
                        <label class="form-label">Utilisateurs sélectionnés</label>
                        <div id="selectedUsersList" class="border rounded p-2" style="min-height: 200px; max-height: 300px; overflow-y: auto;">
                            <div class="text-muted text-center py-5">
                                <i class="fas fa-user-friends fa-2x mb-3"></i><br>
                                Aucun utilisateur sélectionné
                            </div>
                        </div>
                        <div class="mt-2 text-end">
                            <small class="text-muted">
                                <span id="selectedCount">0</span> utilisateur(s) sélectionné(s)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column: Email Composition -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Composition de l'email
                    </h6>
                </div>
                <div class="card-body">
                    <form id="emailForm">
                        @csrf
                        <input type="hidden" name="user_ids" id="user_ids">

                        <!-- Template Selection -->
                        <div class="mb-3">
                            <label class="form-label">Modèle d'email</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 template-btn"
                                            data-template="default" onclick="selectTemplate('default')">
                                        <i class="fas fa-envelope me-1"></i> Standard
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-success w-100 template-btn"
                                            data-template="notification" onclick="selectTemplate('notification')">
                                        <i class="fas fa-bell me-1"></i> Notification
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-info w-100 template-btn"
                                            data-template="newsletter" onclick="selectTemplate('newsletter')">
                                        <i class="fas fa-newspaper me-1"></i> Newsletter
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-warning w-100 template-btn"
                                            data-template="important" onclick="selectTemplate('important')">
                                        <i class="fas fa-exclamation-circle me-1"></i> Important
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="emailSubject" class="form-label">Sujet *</label>
                            <input type="text" class="form-control" id="emailSubject"
                                   name="subject" required placeholder="Sujet de l'email...">
                        </div>

                        <!-- Message Content -->
                        <div class="mb-3">
                            <label for="emailMessage" class="form-label">Message *</label>
                            <textarea class="form-control" id="emailMessage" name="message"
                                      rows="12" required placeholder="Contenu de votre message..."></textarea>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Variables disponibles: {{ '{name}' }} (nom), {{ '{email}' }} (email),
                                    {{ '{date}' }} (date), {{ '{company}' }} ({{ config('app.name') }})
                                </small>
                            </div>
                        </div>

                        <!-- Preview Button -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="previewEmail()">
                                <i class="fas fa-eye me-1"></i> Aperçu de l'email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Preview -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Send Options -->
                    <div class="mb-4">
                        <label class="form-label">Options d'envoi</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sendImmediately" checked>
                            <label class="form-check-label" for="sendImmediately">
                                Envoyer immédiatement
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sendCopyToAdmin">
                            <label class="form-check-label" for="sendCopyToAdmin">
                                Copie à l'administrateur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="saveAsTemplate">
                            <label class="form-check-label" for="saveAsTemplate">
                                Sauvegarder comme modèle
                            </label>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="mb-4" id="scheduleContainer" style="display: none;">
                        <label for="scheduleDate" class="form-label">Programmer l'envoi</label>
                        <input type="datetime-local" class="form-control" id="scheduleDate">
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-4">
                        <label class="form-label">Actions rapides</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="sendEmail()">
                                <i class="fas fa-paper-plane me-1"></i> Envoyer l'email
                            </button>
                            <button type="button" class="btn btn-success" onclick="sendWelcomeEmails()">
                                <i class="fas fa-hand-wave me-1"></i> Envoyer bienvenue
                            </button>
                            <button type="button" class="btn btn-info" onclick="testEmail()">
                                <i class="fas fa-vial me-1"></i> Tester l'email
                            </button>
                            <button type="button" class="btn btn-warning" onclick="resetForm()">
                                <i class="fas fa-redo me-1"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="border-top pt-3">
                        <h6 class="mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <div class="text-primary fw-bold" id="statsToday">0</div>
                                        <small class="text-muted">Aujourd'hui</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <div class="text-success fw-bold" id="statsWeek">0</div>
                                        <small class="text-muted">Cette semaine</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <div class="text-info fw-bold" id="statsMonth">0</div>
                                        <small class="text-muted">Ce mois</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <div class="text-warning fw-bold" id="statsTotal">0</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des utilisateurs
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Inscription</th>
                            <th>Dernière connexion</th>
                            <th>Emails reçus</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Users will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Aperçu de l'email
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="emailPreview" class="border rounded p-3 bg-light">
                    <!-- Preview will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="sendEmail()">
                    <i class="fas fa-paper-plane me-1"></i> Envoyer maintenant
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tester la configuration email</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="testEmailForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="testEmailAddress">Adresse email de test</label>
                        <input type="email" class="form-control" id="testEmailAddress"
                               value="{{ auth()->user()->email }}" required>
                        <small class="form-text text-muted">Entrez une adresse email pour envoyer un email de test.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer l'email de test</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let allUsers = [];
    let selectedUsers = new Set();
    let selectedTemplate = 'default';

    document.addEventListener('DOMContentLoaded', function() {
        // Load users and statistics
        loadUsers();
        loadStatistics();

        // Initialize form
        initializeEmailForm();

        // Handle schedule checkbox
        const sendImmediately = document.getElementById('sendImmediately');
        const scheduleContainer = document.getElementById('scheduleContainer');

        sendImmediately.addEventListener('change', function() {
            scheduleContainer.style.display = this.checked ? 'none' : 'block';
        });

        // Handle select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                if (this.checked) {
                    selectedUsers.add(parseInt(checkbox.value));
                } else {
                    selectedUsers.delete(parseInt(checkbox.value));
                }
            });
            updateSelectedUsers();
        });

        // Load templates
        loadTemplates();
    });

    function loadUsers() {
        fetch('{{ route("admin.emails.get-users") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allUsers = data.users;
                    renderUsersTable();
                    updateUserCounts();
                }
            })
            .catch(error => console.error('Error loading users:', error));
    }

    function renderUsersTable() {
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML = '';

        allUsers.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="checkbox" class="user-checkbox" value="${user.id}"
                           onchange="toggleUserSelection(${user.id}, this.checked)">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title bg-primary rounded-circle text-white">
                                ${user.name.charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div>
                            <strong>${user.name}</strong>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="mailto:${user.email}" class="text-primary">
                        ${user.email}
                    </a>
                </td>
                <td>
                    <span class="badge ${user.utype === 'ADM' ? 'bg-danger' : 'bg-secondary'}">
                        ${user.utype === 'ADM' ? 'Administrateur' : 'Utilisateur'}
                    </span>
                </td>
                <td>${new Date(user.created_at).toLocaleDateString('fr-FR')}</td>
                <td>${user.last_login_at ? new Date(user.last_login_at).toLocaleDateString('fr-FR') : 'Jamais'}</td>
                <td>
                    <span class="badge bg-info">${user.email_count || 0}</span>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="sendSingleEmail(${user.id})">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="btn btn-outline-success" onclick="sendWelcomeEmail(${user.id})">
                            <i class="fas fa-hand-wave"></i>
                        </button>
                        <button class="btn btn-outline-info" onclick="viewUserDetails(${user.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function toggleUserSelection(userId, isSelected) {
        if (isSelected) {
            selectedUsers.add(userId);
        } else {
            selectedUsers.delete(userId);
            document.getElementById('selectAll').checked = false;
        }
        updateSelectedUsers();
    }

    function updateSelectedUsers() {
        const selectedList = document.getElementById('selectedUsersList');
        const selectedCount = document.getElementById('selectedCount');
        const userIdsInput = document.getElementById('user_ids');

        // Update count
        selectedCount.textContent = selectedUsers.size;
        userIdsInput.value = Array.from(selectedUsers).join(',');

        // Update selected users list
        if (selectedUsers.size === 0) {
            selectedList.innerHTML = `
                <div class="text-muted text-center py-5">
                    <i class="fas fa-user-friends fa-2x mb-3"></i><br>
                    Aucun utilisateur sélectionné
                </div>
            `;
        } else {
            let html = '';
            Array.from(selectedUsers).forEach(userId => {
                const user = allUsers.find(u => u.id === userId);
                if (user) {
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                            <div>
                                <strong>${user.name}</strong><br>
                                <small class="text-muted">${user.email}</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeUser(${userId})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }
            });
            selectedList.innerHTML = html;
        }
    }

    function removeUser(userId) {
        selectedUsers.delete(userId);
        document.querySelector(`.user-checkbox[value="${userId}"]`).checked = false;
        updateSelectedUsers();
    }

    function selectAllUsers() {
        allUsers.forEach(user => {
            selectedUsers.add(user.id);
            const checkbox = document.querySelector(`.user-checkbox[value="${user.id}"]`);
            if (checkbox) checkbox.checked = true;
        });
        document.getElementById('selectAll').checked = true;
        updateSelectedUsers();
    }

    function selectActiveUsers() {
        // For now, select all users. You can implement active user filtering
        selectAllUsers();
    }

    function selectAdmins() {
        allUsers.forEach(user => {
            if (user.utype === 'ADM') {
                selectedUsers.add(user.id);
                const checkbox = document.querySelector(`.user-checkbox[value="${user.id}"]`);
                if (checkbox) checkbox.checked = true;
            }
        });
        updateSelectedUsers();
    }

    function deselectAll() {
        selectedUsers.clear();
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        updateSelectedUsers();
    }

    function selectTemplate(template) {
        selectedTemplate = template;

        // Update button styles
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.template === template) {
                btn.classList.add('active');
            }
        });

        // Load template content if available
        loadTemplateContent(template);
    }

    function loadTemplateContent(template) {
        // You can predefine template contents here
        const templates = {
            'default': {
                subject: 'Message important de {{ config("app.name") }}',
                message: 'Bonjour {name},\n\nCeci est un message important de notre équipe.\n\nCordialement,\nL\'équipe {{ config("app.name") }}'
            },
            'notification': {
                subject: 'Notification - {{ config("app.name") }}',
                message: 'Bonjour {name},\n\nNous avons une notification importante à vous communiquer.\n\nCordialement,\nL\'équipe {{ config("app.name") }}'
            },
            'newsletter': {
                subject: 'Newsletter - {{ config("app.name") }}',
                message: 'Bonjour {name},\n\nDécouvrez notre dernière newsletter avec les nouveautés et promotions.\n\nCordialement,\nL\'équipe {{ config("app.name") }}'
            },
            'important': {
                subject: 'URGENT: Message important',
                message: 'Bonjour {name},\n\nCeci est un message urgent nécessitant votre attention.\n\nCordialement,\nL\'équipe {{ config("app.name") }}'
            }
        };

        if (templates[template]) {
            document.getElementById('emailSubject').value = templates[template].subject;
            document.getElementById('emailMessage').value = templates[template].message;
        }
    }

    function previewEmail() {
        const subject = document.getElementById('emailSubject').value;
        const message = document.getElementById('emailMessage').value;

        if (!subject || !message) {
            Swal.fire({
                icon: 'warning',
                title: 'Champs manquants',
                text: 'Veuillez remplir le sujet et le message avant de prévisualiser.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Create preview HTML
        const previewHtml = `
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; color: white; text-align: center;">
                    <h2 style="margin: 0;">${subject}</h2>
                    <p style="margin: 10px 0 0 0;">Email prévisualisé pour [Nom du destinataire]</p>
                </div>
                <div style="padding: 30px; background: white;">
                    ${message.replace(/\n/g, '<br>').replace(/{name}/g, '[Nom du destinataire]')}
                </div>
                <div style="padding: 20px; background: #f8f9fa; text-align: center; border-top: 1px solid #dee2e6;">
                    <p style="margin: 0; color: #6c757d; font-size: 12px;">
                        Cet email a été envoyé par {{ config("app.name") }}<br>
                        ${new Date().toLocaleDateString('fr-FR')}
                    </p>
                </div>
            </div>
        `;

        document.getElementById('emailPreview').innerHTML = previewHtml;
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    }

    function sendEmail() {
        if (selectedUsers.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Aucun utilisateur sélectionné',
                text: 'Veuillez sélectionner au moins un utilisateur avant d\'envoyer un email.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const subject = document.getElementById('emailSubject').value;
        const message = document.getElementById('emailMessage').value;

        if (!subject || !message) {
            Swal.fire({
                icon: 'warning',
                title: 'Champs manquants',
                text: 'Veuillez remplir le sujet et le message avant d\'envoyer.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const sendCopy = document.getElementById('sendCopyToAdmin').checked;
        const scheduleDate = !document.getElementById('sendImmediately').checked
            ? document.getElementById('scheduleDate').value
            : null;

        Swal.fire({
            title: 'Confirmer l\'envoi',
            html: `
                <div class="text-start">
                    <p>Êtes-vous sûr de vouloir envoyer cet email ?</p>
                    <div class="alert alert-info p-2">
                        <strong>Destinataires:</strong> ${selectedUsers.size} utilisateur(s)<br>
                        <strong>Sujet:</strong> ${subject}<br>
                        ${scheduleDate ? `<strong>Programmation:</strong> ${new Date(scheduleDate).toLocaleString('fr-FR')}` : ''}
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('{{ route("admin.emails.send-bulk") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_ids: Array.from(selectedUsers),
                        subject: subject,
                        message: message,
                        template: selectedTemplate,
                        send_copy: sendCopy,
                        schedule_date: scheduleDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Emails envoyés !',
                    html: `
                        <p>${result.value.message}</p>
                        ${result.value.failed ?
                          `<p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>
                           ${result.value.failed.count} échec(s)</p>` : ''}
                    `,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset form
                    resetForm();
                    // Reload statistics
                    loadStatistics();
                });
            }
        });
    }

    function sendSingleEmail(userId) {
        const user = allUsers.find(u => u.id === userId);
        if (!user) return;

        Swal.fire({
            title: 'Envoyer un email',
            html: `
                <div class="text-start">
                    <p>Envoyer un email à <strong>${user.name}</strong> (${user.email})</p>
                    <div class="mb-3">
                        <label class="form-label">Sujet</label>
                        <input type="text" id="singleSubject" class="form-control"
                               placeholder="Sujet de l'email..." value="Message de {{ config('app.name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="singleMessage" class="form-control" rows="5"
                                  placeholder="Votre message...">Bonjour ${user.name},\n\n</textarea>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const subject = document.getElementById('singleSubject').value;
                const message = document.getElementById('singleMessage').value;

                if (!subject || !message) {
                    Swal.showValidationMessage('Veuillez remplir le sujet et le message');
                    return false;
                }

                return fetch('{{ route("admin.emails.send-user") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        subject: subject,
                        message: message,
                        template: 'default'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email envoyé !',
                    text: result.value.message,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function sendWelcomeEmail(userId) {
        Swal.fire({
            title: 'Envoyer un email de bienvenue',
            text: 'Êtes-vous sûr de vouloir envoyer un email de bienvenue à cet utilisateur ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-hand-wave me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('{{ route("admin.emails.send-welcome") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email de bienvenue envoyé !',
                    text: result.value.message,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function sendWelcomeEmails() {
        if (selectedUsers.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Aucun utilisateur sélectionné',
                text: 'Veuillez sélectionner au moins un utilisateur avant d\'envoyer les emails de bienvenue.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Envoyer les emails de bienvenue',
            html: `
                <div class="text-start">
                    <p>Êtes-vous sûr de vouloir envoyer des emails de bienvenue aux utilisateurs sélectionnés ?</p>
                    <div class="alert alert-info p-2">
                        <strong>Destinataires:</strong> ${selectedUsers.size} utilisateur(s)<br>
                        <strong>Type:</strong> Email de bienvenue
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-hand-wave me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Send welcome emails one by one
                const promises = Array.from(selectedUsers).map(userId => {
                    return fetch('{{ route("admin.emails.send-welcome") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(response => response.json());
                });

                return Promise.all(promises)
                    .then(results => {
                        const successCount = results.filter(r => r.success).length;
                        const failedCount = results.filter(r => !r.success).length;

                        return {
                            success: true,
                            message: `${successCount} email(s) de bienvenue envoyé(s) sur ${results.length}`,
                            failed: failedCount > 0 ? { count: failedCount } : null
                        };
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Erreur: ${error.message}`);
                    });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Emails de bienvenue envoyés !',
                    html: `
                        <p>${result.value.message}</p>
                        ${result.value.failed ?
                          `<p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>
                           ${result.value.failed.count} échec(s)</p>` : ''}
                    `,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function testEmail() {
        if (selectedUsers.size === 0) {
            // Test with admin's email
            const testModal = new bootstrap.Modal(document.getElementById('testEmailModal'));
            testModal.show();
        } else {
            // Send test to selected users
            const subject = "Test d'email - {{ config('app.name') }}";
            const message = "Ceci est un email de test envoyé depuis le panneau d'administration.\n\nSi vous recevez cet email, la configuration fonctionne correctement.\n\nCordialement,\nL'équipe {{ config('app.name') }}";

            // Add test users to the form temporarily
            const originalSubject = document.getElementById('emailSubject').value;
            const originalMessage = document.getElementById('emailMessage').value;

            document.getElementById('emailSubject').value = subject;
            document.getElementById('emailMessage').value = message;

            sendEmail();

            // Restore original values
            document.getElementById('emailSubject').value = originalSubject;
            document.getElementById('emailMessage').value = originalMessage;
        }
    }

    function testEmailConfiguration() {
        const testModal = new bootstrap.Modal(document.getElementById('testEmailModal'));
        testModal.show();
    }

    function resetForm() {
        Swal.fire({
            title: 'Réinitialiser le formulaire',
            text: 'Êtes-vous sûr de vouloir réinitialiser tous les champs ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, réinitialiser',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('emailForm').reset();
                deselectAll();
                selectTemplate('default');
                document.getElementById('sendImmediately').checked = true;
                document.getElementById('scheduleContainer').style.display = 'none';

                Swal.fire({
                    icon: 'success',
                    title: 'Formulaire réinitialisé',
                    text: 'Tous les champs ont été réinitialisés.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    function loadStatistics() {
        fetch('{{ route("admin.emails.statistics") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalUsers').textContent = data.total_users || 0;
                    document.getElementById('sentEmails').textContent = data.total_sent || 0;
                    document.getElementById('successRate').textContent = data.success_rate || '0%';
                    document.getElementById('monthEmails').textContent = data.month_sent || 0;

                    // Update stats cards
                    document.getElementById('statsToday').textContent = data.today || 0;
                    document.getElementById('statsWeek').textContent = data.week || 0;
                    document.getElementById('statsMonth').textContent = data.month || 0;
                    document.getElementById('statsTotal').textContent = data.total_sent || 0;
                }
            })
            .catch(error => console.error('Error loading statistics:', error));
    }

    function updateUserCounts() {
        document.getElementById('totalUsers').textContent = allUsers.length;
    }

    function searchUsers() {
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const roleFilter = document.getElementById('filterRole').value;

        // Filter users based on search and filters
        const filteredUsers = allUsers.filter(user => {
            // Search term
            const matchesSearch = user.name.toLowerCase().includes(searchTerm) ||
                                 user.email.toLowerCase().includes(searchTerm);

            // Status filter (you need to implement user status logic)
            let matchesStatus = true;
            if (statusFilter === 'active') {
                // Example: active if logged in within last 30 days
                matchesStatus = user.last_login_at &&
                               (new Date() - new Date(user.last_login_at)) < (30 * 24 * 60 * 60 * 1000);
            } else if (statusFilter === 'inactive') {
                matchesStatus = !user.last_login_at ||
                               (new Date() - new Date(user.last_login_at)) >= (30 * 24 * 60 * 60 * 1000);
            }

            // Role filter
            const matchesRole = !roleFilter || user.utype === roleFilter;

            return matchesSearch && matchesStatus && matchesRole;
        });

        // Update table with filtered users
        renderFilteredUsers(filteredUsers);
    }

    function renderFilteredUsers(users) {
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML = '';

        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="checkbox" class="user-checkbox" value="${user.id}"
                           ${selectedUsers.has(user.id) ? 'checked' : ''}
                           onchange="toggleUserSelection(${user.id}, this.checked)">
                </td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>
                    <span class="badge ${user.utype === 'ADM' ? 'bg-danger' : 'bg-secondary'}">
                        ${user.utype === 'ADM' ? 'Administrateur' : 'Utilisateur'}
                    </span>
                </td>
                <td>${new Date(user.created_at).toLocaleDateString('fr-FR')}</td>
                <td>${user.last_login_at ? new Date(user.last_login_at).toLocaleDateString('fr-FR') : 'Jamais'}</td>
                <td>${user.email_count || 0}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="sendSingleEmail(${user.id})">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function viewUserDetails(userId) {
        window.open(`/admin/user/view/${userId}`, '_blank');
    }

    function loadTemplates() {
        // You can load templates from server or keep local
        selectTemplate('default');
    }

    function initializeEmailForm() {
        // Set default subject
        document.getElementById('emailSubject').value = 'Message de {{ config("app.name") }}';

        // Set default message
        document.getElementById('emailMessage').value = 'Bonjour {name},\n\nNous vous contactons pour vous informer d\'une importante mise à jour.\n\nCordialement,\nL\'équipe {{ config("app.name") }}';

        // Initialize template selection
        selectTemplate('default');
    }

    // Handle test email form submission
    document.getElementById('testEmailForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('testEmailAddress').value;

        fetch('{{ route("admin.emails.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                test_email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email de test envoyé !',
                    text: 'Vérifiez votre boîte de réception.',
                    confirmButtonText: 'OK'
                });

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('testEmailModal'));
                modal.hide();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message || 'Échec de l\'envoi de l\'email de test.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue.',
                confirmButtonText: 'OK'
            });
        });
    });
</script>

<style>
    .template-btn.active {
        background-color: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
    }
</style>
@endsection
