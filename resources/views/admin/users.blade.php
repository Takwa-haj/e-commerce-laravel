
@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Gestion des Utilisateurs</h3>
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
                    <div class="text-tiny">Utilisateurs</div>
                </li>
            </ul>
        </div>

        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="wg-box">
            <!-- Formulaire de Recherche -->
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.users') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher par nom ou email..."
                                   class="" name="query" value="{{ request()->input('query') ?? '' }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            @if(request()->filled('query'))
            <div class="alert alert-info mb-3">
                Résultats de recherche pour: <strong>{{ request()->input('query') }}</strong>
                <a href="{{ route('admin.users') }}" class="float-end">Afficher tous</a>
            </div>
            @endif

            <div class="wg-table table-all-user">
                <div class="table-outer">
                    <table class="table">
                        <thead class="thead">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Date d'Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @if($users->count() > 0)
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="flex items-center gap10">
                                            <div class="image">
                                                @if($user->avatar)
                                                    <img src="{{ asset('uploads/avatars/' . $user->avatar) }}"
                                                         alt="{{ $user->name }}" class="user-avatar">
                                                @else
                                                    <div class="avatar-placeholder">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="name">
                                                <a href="{{ route('admin.user.view', $user->id) }}"
                                                   class="body-text user-name-link">
                                                    {{ $user->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->numero ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.user.update.role', $user->id) }}" method="POST" class="role-form">
                                            @csrf
                                            @method('PUT')
                                            <select name="utype" class="role-select" data-user-id="{{ $user->id }}">
                                                <option value="USR" {{ $user->utype == 'USR' ? 'selected' : '' }}>Utilisateur</option>
                                                <option value="ADM" {{ $user->utype == 'ADM' ? 'selected' : '' }}>Administrateur</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="flex items-center gap10">
                                            <!-- Bouton Voir les Détails -->
                                            <a href="{{ route('admin.user.view', $user->id) }}"
                                               class="tf-button style-2 btn-view" title="Voir les détails">
                                                <i class="icon-eye"></i>
                                            </a>

                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('admin.user-edit', $user->id) }}"
                                               class="tf-button style-2 btn-edit" title="Modifier l'utilisateur">
                                                <i class="icon-edit"></i>
                                            </a>

                                            @if(auth()->id() != $user->id)
                                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="tf-button btn-delete delete-user-btn" title="Supprimer l'utilisateur">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-tiny text-muted">Utilisateur actuel</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        @if(request()->filled('query'))
                                            Aucun utilisateur trouvé pour "{{ request()->input('query') }}".
                                        @else
                                            Aucun utilisateur disponible.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-tiny">
                    @if($users->count() > 0)
                        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                    @else
                        Aucun utilisateur à afficher
                    @endif
                </div>
                @if($users->hasPages())
                <div class="pagination">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Soumission automatique du formulaire de changement de rôle
    $('.role-select').on('change', function() {
        const form = $(this).closest('form');
        const newRole = $(this).val();
        const userName = $(this).closest('tr').find('.user-name-link').text().trim();

        if (newRole === 'ADM') {
            Swal.fire({
                title: 'Promouvoir en Administrateur?',
                html: `Êtes-vous sûr de vouloir promouvoir <strong>${userName}</strong> au rôle d'administrateur?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, promouvoir',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    // Réinitialiser à la valeur précédente
                    $(this).val($(this).data('previous-value'));
                }
            });
        } else if (newRole === 'USR' && $(this).data('previous-value') === 'ADM') {
            Swal.fire({
                title: 'Rétrograder en Utilisateur?',
                html: `Êtes-vous sûr de vouloir rétrograder <strong>${userName}</strong> au rôle d'utilisateur?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, rétrograder',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    // Réinitialiser à la valeur précédente
                    $(this).val('ADM');
                }
            });
        } else {
            form.submit();
        }
    });

    // Stocker la valeur précédente
    $('.role-select').each(function() {
        $(this).data('previous-value', $(this).val());
    });

    // Confirmation de suppression d'utilisateur
    $('.delete-user-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const userName = $(this).closest('tr').find('.user-name-link').text().trim();

        Swal.fire({
            title: 'Êtes-vous sûr?',
            html: `Vous êtes sur le point de supprimer l'utilisateur <strong>${userName}</strong>. Cette action est irréversible!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Effacer la recherche en cliquant sur "Afficher tous"
    $('.clear-search').on('click', function() {
        $('input[name="query"]').val('');
        $('form.form-search').submit();
    });
});
</script>

<style>
/* Styles du formulaire de recherche */
.wg-filter {
    width: 100%;
}

.form-search {
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 500px;
}

.form-search .name {
    flex-grow: 1;
}

.form-search input {
    width: 100%;
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-search input:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
}

.button-submit button {
    padding: 8px 15px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.button-submit button:hover {
    background: #45a049;
}

/* Styles de l'avatar utilisateur */
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #4CAF50;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    border: 2px solid #ddd;
}

/* Styles du tableau */
.table td, .table th {
    vertical-align: middle;
    padding: 12px 15px;
}

.user-name-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.user-name-link:hover {
    color: #4CAF50;
    text-decoration: underline;
}

/* Styles de la sélection de rôle */
.role-select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    cursor: pointer;
    font-size: 14px;
    transition: border-color 0.3s;
}

.role-select:focus {
    outline: none;
    border-color: #4CAF50;
}

.role-select:hover {
    border-color: #999;
}

/* Styles des boutons d'action */
.btn-view {
    padding: 5px 10px;
    min-width: auto;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-view:hover {
    background: #0b7dda;
}

.btn-edit {
    padding: 5px 10px;
    min-width: auto;
    background: #FF9800;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-edit:hover {
    background: #e68900;
}

.btn-delete {
    padding: 5px 10px;
    min-width: auto;
    background: #f44336;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-delete:hover {
    background: #d32f2f;
}

/* Styles des alertes */
.alert {
    padding: 12px 20px;
    border-radius: 4px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-info a {
    color: #0c5460;
    text-decoration: underline;
    font-weight: bold;
}

.alert-info a:hover {
    color: #062c33;
}

/* État vide */
.text-center.py-4 {
    font-style: italic;
    color: #666;
}

/* Responsive */
@media (max-width: 768px) {
    .form-search {
        flex-direction: column;
        align-items: stretch;
    }

    .table td, .table th {
        padding: 8px 10px;
        font-size: 14px;
    }

    .flex.items-center.justify-between.mt-4 {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}
</style>
@endpush
