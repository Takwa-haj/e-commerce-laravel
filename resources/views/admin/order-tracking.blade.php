@extends('layouts.admin')
@section('content')
<style>
    .tracking-timeline {
        position: relative;
        padding-left: 30px;
    }
    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e0e0e0;
    }
    .timeline-step {
        position: relative;
        margin-bottom: 20px;
        padding: 10px;
        background: white;
        border-radius: 8px;
        border-left: 4px solid #000000;
    }
    .timeline-step.completed {
        border-left-color: #40c710;
    }
    .timeline-step.current {
        border-left-color: #2196F3;
    }
    .timeline-step::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 15px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #6e6e6e;
    }
    .timeline-step.completed::before {
        background-color: #40c710;
    }
    .timeline-step.current::before {
        background-color: #2196F3;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Suivi des Commandes</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Suivi des commandes</div>
                </li>
            </ul>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="wg-box">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>N° de Suivi</th>
                            <th>Localisation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($order->status == 'delivered')
                                    <span class="badge bg-success">Livrée</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Expédiée</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-warning">En traitement</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Annulée</span>
                                @else
                                    <span class="badge bg-info">Commandée</span>
                                @endif
                            </td>
                            <td>{{ $order->tracking_number ?? 'Non défini' }}</td>
                            <td>{{ $order->current_location ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#trackingModal{{ $order->id }}">
                                    <i class="icon-edit"></i> Modifier
                                </button>
                                <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="icon-eye"></i> Détails
                                </a>
                            </td>
                        </tr>

                        <!-- Tracking Update Modal -->
                        <div class="modal fade" id="trackingModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('admin.order.tracking.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Suivi Commande #{{ $order->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Statut</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Commandée</option>
                                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Annulée</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Numéro de suivi</label>
                                                    <input type="text" name="tracking_number"
                                                           class="form-control"
                                                           value="{{ $order->tracking_number ?? '' }}"
                                                           placeholder="TRK123456789">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Localisation actuelle</label>
                                                    <input type="text" name="current_location"
                                                           class="form-control"
                                                           value="{{ $order->current_location ?? '' }}"
                                                           placeholder="Ex: Entrepôt Paris, Centre de tri Lyon">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="form-label">Notes de suivi</label>
                                                    <textarea name="notes" class="form-control" rows="3"
                                                              placeholder="Informations supplémentaires...">{{ $order->tracking_notes ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <h6>Historique du suivi :</h6>
                                                <div class="tracking-timeline mt-3">
                                                    <div class="timeline-step {{ in_array($order->status, ['delivered', 'shipped', 'processing', 'ordered']) ? 'completed' : '' }}">
                                                        <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                                        <p><strong>Commande passée</strong></p>
                                                    </div>

                                                    @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                                                    <div class="timeline-step {{ in_array($order->status, ['delivered', 'shipped']) ? 'completed' : ($order->status == 'processing' ? 'current' : '') }}">
                                                        <small>{{ $order->shipped_date ? Carbon\Carbon::parse($order->shipped_date)->format('d/m/Y H:i') : 'En attente' }}</small>
                                                        <p><strong>En traitement</strong></p>
                                                    </div>
                                                    @endif

                                                    @if($order->status == 'shipped' || $order->status == 'delivered')
                                                    <div class="timeline-step {{ $order->status == 'delivered' ? 'completed' : ($order->status == 'shipped' ? 'current' : '') }}">
                                                        <small>{{ $order->shipped_date ? Carbon\Carbon::parse($order->shipped_date)->format('d/m/Y H:i') : 'En attente' }}</small>
                                                        <p><strong>Expédiée</strong></p>
                                                    </div>
                                                    @endif

                                                    @if($order->status == 'delivered')
                                                    <div class="timeline-step completed">
                                                        <small>{{ $order->delivered_date ? Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') : 'En attente' }}</small>
                                                        <p><strong>Livrée</strong></p>
                                                    </div>
                                                    @endif

                                                    @if($order->status == 'canceled')
                                                    <div class="timeline-step completed">
                                                        <small>{{ $order->cancelled_date ? Carbon\Carbon::parse($order->cancelled_date)->format('d/m/Y H:i') : 'En attente' }}</small>
                                                        <p><strong>Annulée</strong></p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="icon-save"></i> Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Auto-update status
        setInterval(function() {
            // You can implement auto-refresh here if needed
        }, 30000);
    });
</script>
@endsection
