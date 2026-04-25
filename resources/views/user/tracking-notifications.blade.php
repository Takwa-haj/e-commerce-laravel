@extends('layouts.app')
@section('content')
<main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Notifications de suivi</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>
            <div class="col-lg-10">
                <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                        <div>
                            <h5>Historique des notifications</h5>
                            <p class="text-muted">Mises à jour concernant vos commandes</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" id="mark-all-read">
                                <i class="fa fa-check-double"></i> Tout marquer comme lu
                            </button>
                        </div>
                    </div>

                    @if($notifications->count() > 0)
                    <div class="list-group">
                        @foreach($notifications as $notification)
                        <div class="list-group-item {{ !$notification->is_read ? 'list-group-item-info' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">
                                            @if(!$notification->is_read)
                                            <span class="badge bg-primary me-2">Nouveau</span>
                                            @endif
                                            {{ $notification->title }}
                                        </h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(!$notification->is_read)
                                                <li>
                                                    <a class="dropdown-item mark-as-read"
                                                       href="#"
                                                       data-id="{{ $notification->id }}">
                                                        <i class="fa fa-check me-2"></i>Marquer comme lu
                                                    </a>
                                                </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('user.order.tracking', ['order_id' => $notification->order_id]) }}">
                                                        <i class="fa fa-eye me-2"></i>Voir le suivi
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('user.order.details', ['order_id' => $notification->order_id]) }}">
                                                        <i class="fa fa-file-invoice me-2"></i>Détails de la commande
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small>
                                            <i class="fa fa-clock me-1"></i>
                                            {{ $notification->created_at->format('d/m/Y à H:i') }}
                                            ({{ $notification->created_at->diffForHumans() }})
                                        </small>
                                        <small>
                                            <i class="fa fa-shopping-cart me-1"></i>
                                            Commande #{{ $notification->order_id }}
                                        </small>
                                    </div>
                                    @if($notification->data && isset($notification->data['tracking_number']))
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fa fa-barcode me-1"></i>
                                            Suivi: {{ $notification->data['tracking_number'] }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5>Aucune notification</h5>
                        <p class="text-muted">Vous n'avez aucune notification de suivi pour le moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
$(document).ready(function() {
    // Mark as read
    $('.mark-as-read').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var notificationId = button.data('id');

        $.ajax({
            url: '{{ route("user.tracking.notification.mark-read") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                notification_id: notificationId
            },
            success: function(response) {
                if(response.success) {
                    button.closest('.list-group-item').removeClass('list-group-item-info');
                    button.closest('.dropdown-menu').find('.mark-as-read').remove();
                    updateNotificationCount();
                }
            },
            error: function() {
                alert('Erreur lors du traitement');
            }
        });
    });

    // Mark all as read
    $('#mark-all-read').on('click', function() {
        Swal.fire({
            title: 'Tout marquer comme lu ?',
            text: "Toutes vos notifications seront marquées comme lues.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, marquer tout comme lu',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("user.tracking.notification.mark-all-read") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            $('.list-group-item').removeClass('list-group-item-info');
                            $('.mark-as-read').remove();
                            updateNotificationCount();
                            Swal.fire('Succès', 'Toutes les notifications ont été marquées comme lues.', 'success');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });

    function updateNotificationCount() {
        $.ajax({
            url: '{{ route("user.tracking.notification.count") }}',
            method: 'GET',
            success: function(response) {
                if(response.count > 0) {
                    $('#tracking-notification-count').text(response.count).show();
                } else {
                    $('#tracking-notification-count').hide();
                }
            }
        });
    }
});
</script>
@endpush
@endsection
