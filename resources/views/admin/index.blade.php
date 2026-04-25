@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <!-- Welcome Section with Date & Greeting -->
            <div class="welcome-section mb-30">
                <div class="welcome-content">
                    <div class="welcome-text">
                        <h1 class="welcome-title">Bonjour, {{ Auth::user()->name }} 👋</h1>
                        <p class="welcome-subtitle">{{ now()->format('l d F Y') }} • Voici ce qui se passe avec votre boutique aujourd'hui</p>
                    </div>
                    <div class="welcome-actions">
                        <a href="{{ route('admin.orders') }}" class="btn-action-primary">
                            <i class="fas fa-plus-circle"></i>
                            Nouvelle commande
                        </a>
                        <a href="{{ route('admin.product.add') }}" class="btn-action-secondary">
                            <i class="fas fa-box"></i>
                            Ajouter produit
                        </a>
                        <button class="btn-refresh" onclick="window.location.reload()" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="welcome-stats">
                    <div class="stat-chip">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <span>{{ $pendingOrders }} commandes en attente</span>
                    </div>
                    <div class="stat-chip">
                        <i class="fas fa-star text-warning"></i>
                        <span>{{ $pendingReviews }} avis à modérer</span>
                    </div>
                    <div class="stat-chip">
                        <i class="fas fa-envelope text-info"></i>
                        <span>{{ $unreadMessages }} messages non lus</span>
                    </div>
                    <div class="stat-chip">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        <span>{{ $lowStockProducts }} alertes stock</span>
                    </div>
                </div>
            </div>

            <!-- KPI Cards with Sparklines -->
            <div class="kpi-grid mb-30">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon bg-primary-soft">
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +12.5%
                        </div>
                    </div>
                    <div class="kpi-body">
                        <span class="kpi-label">Chiffre d'affaires</span>
                        <div class="kpi-value">{{ number_format($totalRevenue, 0, ',', ' ') }} €</div>
                        <div class="kpi-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar-day"></i>
                                Aujourd'hui: {{ number_format($todayRevenue, 0, ',', ' ') }} €
                            </span>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="kpi-period">Ce mois</span>
                        <div class="kpi-sparkline" id="sparkline-revenue"></div>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon bg-success-soft">
                            <i class="fas fa-shopping-bag text-success"></i>
                        </div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +8.2%
                        </div>
                    </div>
                    <div class="kpi-body">
                        <span class="kpi-label">Commandes totales</span>
                        <div class="kpi-value">{{ number_format($totalOrders, 0, ',', ' ') }}</div>
                        <div class="kpi-meta">
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                En attente: {{ $pendingOrders }}
                            </span>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="kpi-period">Ce mois</span>
                        <div class="kpi-sparkline" id="sparkline-orders"></div>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon bg-info-soft">
                            <i class="fas fa-users text-info"></i>
                        </div>
                        <div class="kpi-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +15.3%
                        </div>
                    </div>
                    <div class="kpi-body">
                        <span class="kpi-label">Nouveaux clients</span>
                        <div class="kpi-value">{{ number_format($newCustomers, 0, ',', ' ') }}</div>
                        <div class="kpi-meta">
                            <span class="meta-item">
                                <i class="fas fa-user-plus"></i>
                                Aujourd'hui: {{ $todayNewCustomers }}
                            </span>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="kpi-period">Ce mois</span>
                        <div class="kpi-sparkline" id="sparkline-customers"></div>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon bg-warning-soft">
                            <i class="fas fa-ticket-alt text-warning"></i>
                        </div>
                        <div class="kpi-trend neutral">
                            <i class="fas fa-minus"></i>
                            0.5%
                        </div>
                    </div>
                    <div class="kpi-body">
                        <span class="kpi-label">Panier moyen</span>
                        <div class="kpi-value">{{ number_format($averageOrderValue, 2, ',', ' ') }} €</div>
                        <div class="kpi-meta">
                            <span class="meta-item">
                                <i class="fas fa-chart-pie"></i>
                                Conversion: {{ $conversionRate }}%
                            </span>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="kpi-period">Ce mois</span>
                        <div class="kpi-sparkline" id="sparkline-avg"></div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Row - Original Stats Redesigned -->
            <div class="quick-stats-row mb-30">
                <div class="quick-stats-header">
                    <h3 class="section-title">Statistiques détaillées</h3>
                    <div class="quick-stats-legend">
                        <span class="legend-item">
                            <span class="legend-dot" style="background: #2377FC"></span>
                            Commandes
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background: #078407"></span>
                            Livraisons
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background: #FFA500"></span>
                            Attente
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background: #FF0000"></span>
                            Annulations
                        </span>
                    </div>
                </div>

                <div class="quick-stats-grid">
                    <!-- Left Column - Order Stats -->
                    <div class="quick-stats-card">
                        <div class="quick-stats-card-header">
                            <div class="header-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h4>Aperçu des commandes</h4>
                        </div>
                        <div class="quick-stats-card-body">
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                    Commandes totales
                                </div>
                                <div class="stat-row-value">{{ $dashboardDatas[0]->Total }}</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                    Montant total
                                </div>
                                <div class="stat-row-value">{{ number_format($dashboardDatas[0]->TotalAmount, 2, ',', ' ') }} €</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
                                    En attente
                                </div>
                                <div class="stat-row-value">{{ $dashboardDatas[0]->TotalOrdered }}</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
                                    Montant en attente
                                </div>
                                <div class="stat-row-value">{{ number_format($dashboardDatas[0]->TotalOrderedAmount, 2, ',', ' ') }} €</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Delivery Stats -->
                    <div class="quick-stats-card">
                        <div class="quick-stats-card-header">
                            <div class="header-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4>Aperçu des livraisons</h4>
                        </div>
                        <div class="quick-stats-card-body">
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-success" style="font-size: 8px;"></i>
                                    Commandes livrées
                                </div>
                                <div class="stat-row-value">{{ $dashboardDatas[0]->TotalDelivered }}</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-success" style="font-size: 8px;"></i>
                                    Montant livré
                                </div>
                                <div class="stat-row-value">{{ number_format($dashboardDatas[0]->TotalDeliveredAmount, 2, ',', ' ') }} €</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-danger" style="font-size: 8px;"></i>
                                    Commandes annulées
                                </div>
                                <div class="stat-row-value">{{ $dashboardDatas[0]->TotalCancelled }}</div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">
                                    <i class="fas fa-circle text-danger" style="font-size: 8px;"></i>
                                    Montant annulé
                                </div>
                                <div class="stat-row-value">{{ number_format($dashboardDatas[0]->TotalCancelledAmount, 2, ',', ' ') }} €</div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Stats -->
                    <div class="quick-stats-card">
                        <div class="quick-stats-card-header">
                            <div class="header-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4>Indicateurs de performance</h4>
                        </div>
                        <div class="quick-stats-card-body">
                            <div class="stat-row-item">
                                <div class="stat-row-label">Taux de conversion</div>
                                <div class="stat-row-value">
                                    <span class="performance-badge {{ $conversionRate > 5 ? 'success' : ($conversionRate > 2 ? 'warning' : 'danger') }}">
                                        {{ $conversionRate }}%
                                    </span>
                                </div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">Satisfaction client</div>
                                <div class="stat-row-value">
                                    <div class="mini-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                                        @endfor
                                        <span class="rating-value">{{ $avgRating }}/5</span>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">Taux d'annulation</div>
                                <div class="stat-row-value">
                                    <span class="performance-badge {{ $returnRate < 5 ? 'success' : ($returnRate < 10 ? 'warning' : 'danger') }}">
                                        {{ $returnRate }}%
                                    </span>
                                </div>
                            </div>
                            <div class="stat-row-item">
                                <div class="stat-row-label">Produits en stock faible</div>
                                <div class="stat-row-value">
                                    <span class="performance-badge {{ $lowStockProducts == 0 ? 'success' : ($lowStockProducts < 5 ? 'warning' : 'danger') }}">
                                        {{ $lowStockProducts }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Dashboard -->
            <div class="analytics-dashboard mb-30">
                <div class="analytics-header">
                    <div class="analytics-title-group">
                        <h3 class="section-title">Analytique avancée</h3>
                        <div class="analytics-period">
                            <button class="period-btn active" data-period="year">Année</button>
                            <button class="period-btn" data-period="quarter">Trimestre</button>
                            <button class="period-btn" data-period="month">Mois</button>
                        </div>
                    </div>
                    <div id="demo-data-badge" style="display: none;">
                        <span class="demo-badge">
                            <i class="fas fa-info-circle"></i>
                            Données de démonstration
                        </span>
                    </div>
                </div>

                <div class="analytics-grid">
                    <!-- Graph 1: Main Revenue Chart - Area Chart -->
                    <div class="analytics-card main-chart">
                        <div class="analytics-card-header">
                            <div>
                                <h4>Évolution du chiffre d'affaires</h4>
                                <p class="chart-subtitle">Comparaison mensuelle {{ date('Y') }}</p>
                            </div>
                            <div class="chart-actions">
                                <button class="chart-action-btn" onclick="downloadChart('revenue-chart', 'revenus')" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="chart-action-btn" onclick="expandChart('revenue-chart')" title="Agrandir">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        <div class="analytics-card-body">
                            <div id="revenue-chart" style="height: 350px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Graph 2: Order Status Distribution - Donut Chart -->
                    <div class="analytics-card status-chart">
                        <div class="analytics-card-header">
                            <h4>Répartition des commandes</h4>
                            <div class="chart-legend-mini">
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #078407"></span>
                                    Livrées
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #3b82f6"></span>
                                    Confirmées
                                </span>
                            </div>
                        </div>
                        <div class="analytics-card-body">
                            <div id="order-status-chart" style="height: 280px; width: 100%;"></div>
                            <div class="status-summary">
                                <div class="status-summary-item">
                                    <span class="status-label">Taux de livraison</span>
                                    <span class="status-value" id="delivery-rate">
                                        {{ $totalOrders > 0 ? round(($orderStatusData[0] / $totalOrders) * 100) : 0 }}%
                                    </span>
                                </div>
                                <div class="status-summary-item">
                                    <span class="status-label">Taux d'annulation</span>
                                    <span class="status-value" id="cancellation-rate">
                                        {{ $totalOrders > 0 ? round(($orderStatusData[3] / $totalOrders) * 100) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graph 3: Revenue by Status - Bar Chart -->
                    <div class="analytics-card revenue-chart">
                        <div class="analytics-card-header">
                            <h4>Revenus par statut</h4>
                            <div class="chart-legend-mini">
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #2377FC"></span>
                                    Total
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #FFA500"></span>
                                    Attente
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #078407"></span>
                                    Livré
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #FF0000"></span>
                                    Annulé
                                </span>
                            </div>
                        </div>
                        <div class="analytics-card-body">
                            <div id="line-chart-8" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Graph 4: Daily Sales Trend - Line Chart -->
                    <div class="analytics-card daily-chart">
                        <div class="analytics-card-header">
                            <h4>Tendance quotidienne des ventes</h4>
                            <div class="chart-legend-mini">
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #8b5cf6"></span>
                                    Cette semaine
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #ec4899"></span>
                                    Semaine dernière
                                </span>
                            </div>
                        </div>
                        <div class="analytics-card-body">
                            <div id="daily-sales-chart" style="height: 280px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Graph 5: Top Categories - Pie Chart -->
                    <div class="analytics-card categories-chart">
                        <div class="analytics-card-header">
                            <h4>Ventes par catégorie</h4>
                            <a href="{{ route('admin.categories') }}" class="insights-link">
                                Voir tout
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="analytics-card-body">
                            <div id="categories-chart" style="height: 250px; width: 100%;"></div>
                            <div class="categories-list">
                                @foreach($topCategories as $category)
                                <div class="category-item">
                                    <span class="category-name">
                                        <span class="color-dot" style="background: {{ $category->color ?? '#2377FC' }}"></span>
                                        {{ $category->name }}
                                    </span>
                                    <span class="category-value">{{ $category->products_count ?? 0 }} produits</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Graph 6: Customer Acquisition - Radial Bar Chart -->
                    <div class="analytics-card acquisition-chart">
                        <div class="analytics-card-header">
                            <h4>Acquisition clients</h4>
                        </div>
                        <div class="analytics-card-body">
                            <div id="acquisition-chart" style="height: 250px; width: 100%;"></div>
                            <div class="acquisition-stats">
                                <div class="acquisition-stat">
                                    <span class="stat-label">Nouveaux</span>
                                    <span class="stat-value">{{ $newCustomers }}</span>
                                </div>
                                <div class="acquisition-stat">
                                    <span class="stat-label">Fidèles</span>
                                    <span class="stat-value">{{ $repeatCustomers ?? 0 }}</span>
                                </div>
                                <div class="acquisition-stat">
                                    <span class="stat-label">Taux de retour</span>
                                    <span class="stat-value">{{ $repeatRate ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graph 7: Monthly Comparison - Column Chart -->
                    <div class="analytics-card comparison-chart">
                        <div class="analytics-card-header">
                            <h4>Comparaison mensuelle</h4>
                            <div class="chart-legend-mini">
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #2377FC"></span>
                                    {{ date('Y') }}
                                </span>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background: #9ca3af"></span>
                                    {{ date('Y') - 1 }}
                                </span>
                            </div>
                        </div>
                        <div class="analytics-card-body">
                            <div id="comparison-chart" style="height: 280px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Graph 8: Stock Status - Gauge Chart -->
                    <div class="analytics-card stock-chart">
                        <div class="analytics-card-header">
                            <h4>État des stocks</h4>
                        </div>
                        <div class="analytics-card-body">
                            <div id="stock-chart" style="height: 200px; width: 100%;"></div>
                            <div class="stock-stats">
                                <div class="stock-stat">
                                    <span class="stock-label">En stock</span>
                                    <span class="stock-value text-success">{{ $inStockProducts ?? 0 }}</span>
                                </div>
                                <div class="stock-stat">
                                    <span class="stock-label">Stock faible</span>
                                    <span class="stock-value text-warning">{{ $lowStockProducts }}</span>
                                </div>
                                <div class="stock-stat">
                                    <span class="stock-label">Rupture</span>
                                    <span class="stock-value text-danger">{{ $outOfStockProducts ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products & Activity Timeline -->
            <div class="insights-panel mb-30">
                <div class="insights-grid">
                    <!-- Top Products with Progress -->
                    <div class="insights-card">
                        <div class="insights-card-header">
                            <div class="insights-title">
                                <i class="fas fa-crown text-warning"></i>
                                <h4>Top produits</h4>
                            </div>
                            <a href="{{ route('admin.products') }}" class="insights-link">
                                Voir catalogue
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="insights-card-body">
                            @foreach($topProducts as $index => $product)
                            <div class="product-performance-item">
                                <div class="product-rank-badge">{{ $index + 1 }}</div>
                                <div class="product-performance-info">
                                    <div class="product-performance-header">
                                        <div class="product-image-mini">
                                            @if($product->image)
                                            <img src="{{ asset('uploads/products/thumbnails/' . $product->image) }}" alt="{{ $product->name }}">
                                            @else
                                            <div class="product-image-placeholder">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="product-performance-details">
                                            <h5>{{ \Illuminate\Support\Str::limit($product->name, 25) }}</h5>
                                            <span class="product-sku">{{ $product->SKU }}</span>
                                        </div>
                                    </div>
                                    <div class="product-performance-metrics">
                                        <div class="metric">
                                            <span class="metric-label">Ventes</span>
                                            <span class="metric-value">{{ $product->total_sold ?? 0 }}</span>
                                        </div>
                                        <div class="metric">
                                            <span class="metric-label">Revenu</span>
                                            <span class="metric-value">{{ number_format($product->total_revenue ?? 0, 0, ',', ' ') }} €</span>
                                        </div>
                                    </div>
                                    <div class="product-performance-progress">
                                        <div class="progress-label">
                                            <span>Performance</span>
                                            <span>{{ $totalOrders > 0 ? round((($product->total_sold ?? 0) / $totalOrders) * 100, 1) : 0 }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $totalOrders > 0 ? round((($product->total_sold ?? 0) / $totalOrders) * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Activity Timeline & Quick Actions -->
                    <div class="insights-card">
                        <div class="insights-card-header">
                            <div class="insights-title">
                                <i class="fas fa-history text-info"></i>
                                <h4>Activité récente</h4>
                            </div>
                            <span class="live-badge">
                                <span class="live-dot"></span>
                                Live
                            </span>
                        </div>
                        <div class="insights-card-body">
                            <div class="activity-stream">
                                @foreach($recentActivities as $activity)
                                <div class="activity-stream-item">
                                    <div class="activity-stream-icon {{ $activity['color'] }}">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-stream-content">
                                        <div class="activity-stream-header">
                                            <h6>{{ $activity['title'] }}</h6>
                                            <span class="activity-time">{{ $activity['time'] }}</span>
                                        </div>
                                        <p>{{ $activity['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="insights-card-footer">
                            <div class="quick-actions-mini">
                                <a href="{{ route('admin.orders') }}" class="quick-action-mini">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Commandes</span>
                                    @if($pendingOrders > 0)
                                    <span class="badge">{{ $pendingOrders }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.reviews') }}" class="quick-action-mini">
                                    <i class="fas fa-star"></i>
                                    <span>Avis</span>
                                    @if($pendingReviews > 0)
                                    <span class="badge">{{ $pendingReviews }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.contacts') }}" class="quick-action-mini">
                                    <i class="fas fa-envelope"></i>
                                    <span>Messages</span>
                                    @if($unreadMessages > 0)
                                    <span class="badge">{{ $unreadMessages }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.products') }}" class="quick-action-mini">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Stock</span>
                                    @if($lowStockProducts > 0)
                                    <span class="badge">{{ $lowStockProducts }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table - Redesigned -->
            <div class="orders-table-section">
                <div class="orders-table-card">
                    <div class="orders-table-header">
                        <div class="orders-table-title">
                            <i class="fas fa-clock"></i>
                            <h4>Commandes récentes</h4>
                        </div>
                        <div class="orders-table-actions">
                            <div class="table-search">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Rechercher une commande...">
                            </div>
                            <a href="{{ route('admin.orders') }}" class="btn-view-all">
                                Voir toutes les commandes
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="orders-table-body">
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>N° commande</th>
                                        <th>Client</th>
                                        <th>Contact</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Articles</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="order-id">#{{ $order->id }}</span>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar">
                                                    {{ strtoupper(substr($order->name, 0, 1)) }}
                                                </div>
                                                <div class="customer-details">
                                                    <span class="customer-name">{{ $order->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <span class="contact-phone">{{ $order->phone }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="amount-info">
                                                <span class="amount-total">{{ number_format($order->total, 2, ',', ' ') }} €</span>
                                                <span class="amount-subtotal">HT: {{ number_format($order->subtotal, 2, ',', ' ') }} €</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = '';
                                                $statusText = '';
                                                if ($order->status == 'delivered') {
                                                    $statusClass = 'success';
                                                    $statusText = 'Livrée';
                                                } elseif ($order->status == 'canceled') {
                                                    $statusClass = 'danger';
                                                    $statusText = 'Annulée';
                                                } elseif ($order->status == 'confirmed') {
                                                    $statusClass = 'info';
                                                    $statusText = 'Confirmée';
                                                } else {
                                                    $statusClass = 'warning';
                                                    $statusText = 'En attente';
                                                }
                                            @endphp
                                            <span class="status-badge-modern status-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <span class="date">{{ $order->created_at->format('d/m/Y') }}</span>
                                                <span class="time">{{ $order->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="items-badge">{{ $order->orderItems->count() }} articles</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.order.details',['order_id'=>$order->id]) }}"
                                                   class="action-btn view-btn"
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->status != 'delivered' && $order->status != 'canceled')
                                                <button class="action-btn edit-btn" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Professional Color System */
:root {
    --primary: #2377FC;
    --primary-dark: #1e5fc4;
    --primary-light: #e8f0fe;
    --primary-soft: rgba(35, 119, 252, 0.1);
    --success: #078407;
    --success-dark: #056605;
    --success-light: #e6f3e6;
    --success-soft: rgba(7, 132, 7, 0.1);
    --warning: #FFA500;
    --warning-dark: #cc8400;
    --warning-light: #fff3e0;
    --warning-soft: rgba(255, 165, 0, 0.1);
    --danger: #FF0000;
    --danger-dark: #cc0000;
    --danger-light: #ffe6e6;
    --danger-soft: rgba(255, 0, 0, 0.1);
    --info: #3b82f6;
    --info-dark: #2563eb;
    --info-light: #dbeafe;
    --info-soft: rgba(59, 130, 246, 0.1);
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --border-radius-sm: 8px;
    --border-radius-md: 12px;
    --border-radius-lg: 16px;
    --border-radius-xl: 20px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Chart Containers - FIXED */
#revenue-chart,
#order-status-chart,
#line-chart-8,
#daily-sales-chart,
#categories-chart,
#acquisition-chart,
#comparison-chart,
#stock-chart {
    width: 100%;
    min-height: 250px;
    background: #ffffff;
    position: relative;
    z-index: 1;
}

.apexcharts-canvas {
    position: relative;
    z-index: 2;
}

/* Main Layout */
.main-content-inner {
    padding: 30px;
    background: var(--gray-50);
}

.main-content-wrap {
    max-width: 1600px;
    margin: 0 auto;
}

/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--info) 100%);
    border-radius: var(--border-radius-xl);
    padding: 30px;
    color: white;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

.welcome-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');
    background-size: 50px;
    opacity: 0.2;
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.welcome-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: white;
}

.welcome-subtitle {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
}

.welcome-actions {
    display: flex;
    gap: 12px;
}

.btn-action-primary {
    background: white;
    color: var(--primary);
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-action-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--primary-dark);
}

.btn-action-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-action-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.btn-refresh {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(180deg);
}

.welcome-stats {
    display: flex;
    gap: 15px;
    position: relative;
    z-index: 1;
}

.stat-chip {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 8px 16px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-chip i {
    font-size: 14px;
}

/* Section Titles */
.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 40px;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
}

/* KPI Grid */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.kpi-card {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-200);
    position: relative;
    overflow: hidden;
}

.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, var(--primary), var(--info));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.kpi-card:hover::before {
    opacity: 1;
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bg-primary-soft { background: var(--primary-soft); }
.bg-success-soft { background: var(--success-soft); }
.bg-info-soft { background: var(--info-soft); }
.bg-warning-soft { background: var(--warning-soft); }

.kpi-trend {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.kpi-trend.positive {
    background: var(--success-soft);
    color: var(--success);
}

.kpi-trend.neutral {
    background: var(--warning-soft);
    color: var(--warning-dark);
}

.kpi-trend.negative {
    background: var(--danger-soft);
    color: var(--danger);
}

.kpi-body {
    margin-bottom: 20px;
}

.kpi-label {
    font-size: 12px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 6px;
}

.kpi-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 8px;
}

.kpi-meta {
    display: flex;
    gap: 12px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--gray-500);
}

.kpi-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--gray-200);
}

.kpi-period {
    font-size: 12px;
    color: var(--gray-500);
}

.kpi-sparkline {
    width: 80px;
    height: 30px;
}

/* Quick Stats Row */
.quick-stats-row {
    margin-bottom: 30px;
}

.quick-stats-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.quick-stats-legend {
    display: flex;
    gap: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--gray-600);
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}

.quick-stats-card {
    background: white;
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.3s ease;
}

.quick-stats-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
}

.quick-stats-card-header {
    padding: 20px;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 12px;
}

.quick-stats-card-header .header-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-sm);
    background: var(--primary-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 16px;
}

.quick-stats-card-header h4 {
    font-size: 15px;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
}

.quick-stats-card-body {
    padding: 20px;
}

.stat-row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-100);
}

.stat-row-item:last-child {
    border-bottom: none;
}

.stat-row-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--gray-600);
}

.stat-row-value {
    font-weight: 600;
    color: var(--gray-800);
}

.performance-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.performance-badge.success {
    background: var(--success-soft);
    color: var(--success);
}

.performance-badge.warning {
    background: var(--warning-soft);
    color: var(--warning-dark);
}

.performance-badge.danger {
    background: var(--danger-soft);
    color: var(--danger);
}

.mini-rating {
    display: flex;
    align-items: center;
    gap: 2px;
}

.mini-rating .fa-star {
    color: var(--gray-300);
    font-size: 12px;
}

.mini-rating .fa-star.active {
    color: var(--warning);
}

.rating-value {
    margin-left: 6px;
    font-size: 12px;
    color: var(--gray-600);
}

/* Analytics Dashboard */
.analytics-dashboard {
    margin-bottom: 30px;
}

.analytics-header {
    margin-bottom: 20px;
}

.analytics-title-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.analytics-period {
    display: flex;
    gap: 8px;
}

.period-btn {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.3s ease;
}

.period-btn:hover {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
}

.period-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.analytics-card {
    background: white;
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.3s ease;
}

.analytics-card:hover {
    box-shadow: var(--shadow-md);
}

.analytics-card.main-chart {
    grid-column: span 2;
}

.analytics-card.revenue-chart {
    grid-column: span 2;
}

.analytics-card-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.analytics-card-header h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 4px 0;
}

.chart-subtitle {
    font-size: 12px;
    color: var(--gray-500);
    margin: 0;
}

.chart-actions {
    display: flex;
    gap: 8px;
}

.chart-action-btn {
    width: 32px;
    height: 32px;
    border-radius: var(--border-radius-sm);
    background: var(--gray-100);
    border: none;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.chart-action-btn:hover {
    background: var(--primary);
    color: white;
}

.analytics-card-body {
    padding: 20px;
}

.status-summary {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--gray-200);
}

.status-summary-item {
    text-align: center;
}

.status-label {
    display: block;
    font-size: 12px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.status-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-800);
}

/* Insights Panel */
.insights-panel {
    margin-bottom: 30px;
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 20px;
}

.insights-card {
    background: white;
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.3s ease;
}

.insights-card:hover {
    box-shadow: var(--shadow-md);
}

.insights-card-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--gray-50);
}

.insights-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.insights-title i {
    font-size: 18px;
}

.insights-title h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.insights-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.insights-link:hover {
    color: var(--primary-dark);
    gap: 10px;
}

.live-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: var(--danger-soft);
    border-radius: 20px;
    color: var(--danger);
    font-size: 12px;
    font-weight: 600;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--danger);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}

.insights-card-body {
    padding: 20px;
}

.insights-card-footer {
    padding: 20px;
    border-top: 1px solid var(--gray-200);
    background: var(--gray-50);
}

/* Product Performance */
.product-performance-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    border-bottom: 1px solid var(--gray-100);
    transition: all 0.3s ease;
}

.product-performance-item:last-child {
    border-bottom: none;
}

.product-performance-item:hover {
    background: var(--gray-50);
}

.product-rank-badge {
    width: 28px;
    height: 28px;
    border-radius: var(--border-radius-sm);
    background: var(--primary-soft);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
}

.product-performance-info {
    flex: 1;
}

.product-performance-header {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.product-image-mini {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    flex-shrink: 0;
}

.product-image-mini img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-performance-details h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 4px 0;
}

.product-sku {
    font-size: 12px;
    color: var(--gray-500);
}

.product-performance-metrics {
    display: flex;
    gap: 24px;
    margin-bottom: 12px;
}

.metric {
    display: flex;
    flex-direction: column;
}

.metric-label {
    font-size: 11px;
    color: var(--gray-500);
    margin-bottom: 2px;
}

.metric-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--gray-800);
}

.product-performance-progress {
    margin-top: 8px;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.progress {
    height: 6px;
    background: var(--gray-100);
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: var(--primary);
    border-radius: 3px;
}

/* Activity Stream */
.activity-stream {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-stream-item {
    display: flex;
    gap: 16px;
    padding: 12px;
    border-radius: var(--border-radius-md);
    transition: all 0.3s ease;
}

.activity-stream-item:hover {
    background: var(--gray-50);
}

.activity-stream-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.activity-stream-icon.primary { background: var(--primary); }
.activity-stream-icon.success { background: var(--success); }
.activity-stream-icon.warning { background: var(--warning); }
.activity-stream-icon.info { background: var(--info); }

.activity-stream-content {
    flex: 1;
}

.activity-stream-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.activity-stream-header h6 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.activity-time {
    font-size: 11px;
    color: var(--gray-500);
}

.activity-stream-content p {
    font-size: 13px;
    color: var(--gray-600);
    margin: 0;
}

/* Quick Actions Mini */
.quick-actions-mini {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.quick-action-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: white;
    border-radius: var(--border-radius-md);
    text-decoration: none;
    color: var(--gray-700);
    font-size: 13px;
    font-weight: 500;
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
    position: relative;
}

.quick-action-mini:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.quick-action-mini .badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: var(--danger);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 12px;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Modern Table */
.orders-table-section {
    margin-bottom: 30px;
}

.orders-table-card {
    background: white;
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.orders-table-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--gray-50);
}

.orders-table-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.orders-table-title i {
    font-size: 18px;
    color: var(--primary);
}

.orders-table-title h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.orders-table-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.table-search {
    position: relative;
}

.table-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 14px;
}

.table-search input {
    padding: 8px 12px 8px 36px;
    border: 1px solid var(--gray-200);
    border-radius: 30px;
    font-size: 13px;
    width: 240px;
    transition: all 0.3s ease;
}

.table-search input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: var(--primary);
    color: white;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: var(--primary-dark);
    transform: translateX(2px);
}

/* Modern Table Styling */
.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    padding: 16px 20px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: var(--gray-50);
    border-bottom: 2px solid var(--gray-200);
}

.table-modern tbody td {
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.order-id {
    font-weight: 700;
    color: var(--primary);
    font-size: 14px;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary-soft);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.customer-details {
    display: flex;
    flex-direction: column;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 14px;
}

.contact-info {
    display: flex;
    flex-direction: column;
}

.contact-phone {
    font-size: 13px;
    color: var(--gray-600);
}

.amount-info {
    display: flex;
    flex-direction: column;
}

.amount-total {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 15px;
}

.amount-subtotal {
    font-size: 11px;
    color: var(--gray-500);
}

.status-badge-modern {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.status-success {
    background: var(--success-soft);
    color: var(--success);
}

.status-danger {
    background: var(--danger-soft);
    color: var(--danger);
}

.status-warning {
    background: var(--warning-soft);
    color: var(--warning-dark);
}

.status-info {
    background: var(--info-soft);
    color: var(--info-dark);
}

.date-info {
    display: flex;
    flex-direction: column;
}

.date {
    font-size: 13px;
    color: var(--gray-700);
    font-weight: 500;
}

.time {
    font-size: 11px;
    color: var(--gray-500);
}

.items-badge {
    background: var(--gray-100);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: var(--gray-600);
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 34px;
    height: 34px;
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.view-btn {
    background: var(--primary-soft);
    color: var(--primary);
}

.view-btn:hover {
    background: var(--primary);
    color: white;
}

.edit-btn {
    background: var(--warning-soft);
    color: var(--warning-dark);
}

.edit-btn:hover {
    background: var(--warning);
    color: white;
}

/* Categories List */
.categories-list {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--gray-200);
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.category-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--gray-700);
}

.color-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.category-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-800);
}

/* Acquisition Stats */
.acquisition-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--gray-200);
    text-align: center;
}

.acquisition-stat {
    display: flex;
    flex-direction: column;
}

.acquisition-stat .stat-label {
    font-size: 11px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.acquisition-stat .stat-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-800);
}

/* Stock Stats */
.stock-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--gray-200);
    text-align: center;
}

.stock-stat {
    display: flex;
    flex-direction: column;
}

.stock-label {
    font-size: 11px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.stock-value {
    font-size: 18px;
    font-weight: 700;
}

/* Chart Legend Mini */
.chart-legend-mini {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.chart-legend-mini .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--gray-600);
}

/* Demo Data Badge */
.demo-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--warning-soft);
    color: var(--warning-dark);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.demo-badge i {
    font-size: 14px;
}

/* Text Colors */
.text-success { color: #078407 !important; }
.text-warning { color: #FFA500 !important; }
.text-danger { color: #FF0000 !important; }

/* Responsive */
@media (max-width: 1200px) {
    .analytics-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .analytics-card.main-chart,
    .analytics-card.revenue-chart {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .main-content-inner {
        padding: 15px;
    }

    .welcome-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .welcome-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .welcome-stats {
        flex-wrap: wrap;
    }

    .kpi-grid {
        grid-template-columns: 1fr;
    }

    .quick-stats-grid {
        grid-template-columns: 1fr;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
    }

    .analytics-card.main-chart,
    .analytics-card.revenue-chart {
        grid-column: span 1;
    }

    .analytics-title-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .orders-table-actions {
        flex-direction: column;
        align-items: flex-start;
    }

    .table-search input {
        width: 200px;
    }

    .quick-actions-mini {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Print Styles */
@media print {
    .welcome-section,
    .btn-refresh,
    .chart-actions,
    .quick-actions-mini {
        display: none !important;
    }
}

/* Notification */
.notification-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--gray-800);
    color: white;
    padding: 12px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.notification-toast.show {
    transform: translateY(0);
    opacity: 1;
}
</style>
@endpush


@push('scripts')
<!-- Use stable version of ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>

<script>
// Pass PHP data to JavaScript
window.chartData = {
    revenue: @json($revenueData ?? []),
    orderStatus: @json($orderStatusData ?? []),
    amountM: @json($AmountM ?? []),
    orderedAmountM: @json($OrderedAmountM ?? []),
    deliveredAmountM: @json($DeliveredAmountM ?? []),
    canceledAmountM: @json($CanceledAmountM ?? []),
    lastYearRevenue: @json($lastYearRevenueData ?? []),
    topCategories: @json($topCategories ?? []),
    inStock: {{ $inStockProducts ?? 0 }},
    lowStock: {{ $lowStockProducts ?? 0 }},
    outOfStock: {{ $outOfStockProducts ?? 0 }},
    totalProducts: {{ $totalProducts ?? 1 }},
    conversionRate: {{ $conversionRate ?? 2.5 }},
    avgRating: {{ $avgRating ?? 4 }},
    repeatRate: {{ $repeatRate ?? 15 }}
};

document.addEventListener('DOMContentLoaded', function() {
    // Check if ApexCharts is loaded
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts library not loaded!');
        return;
    }

    console.log('ApexCharts v' + ApexCharts.version + ' loaded successfully');

    // Helper function to ensure array has 12 values
    function ensureArray(arr, defaultValue = 0) {
        if (!arr || !Array.isArray(arr) || arr.length === 0) {
            return Array(12).fill(defaultValue);
        }
        return arr;
    }

    // Sample data for empty datasets
    const sampleData = {
        revenue: [12500, 15200, 11800, 19500, 22300, 26800, 24100, 28900, 31500, 34200, 29800, 38500],
        ordered: [5200, 6800, 4900, 8200, 9500, 11200, 10100, 12500, 13800, 15200, 13100, 16800],
        delivered: [6800, 7900, 6200, 10500, 12000, 14500, 12800, 15600, 17200, 18500, 16200, 21000],
        canceled: [500, 600, 700, 800, 800, 1100, 1200, 800, 500, 500, 500, 700],
        lastYear: [10200, 12100, 9800, 16200, 18500, 22100, 19800, 24100, 26800, 28900, 25100, 32500],
        status: [145, 32, 28, 12]
    };

    // Get data with fallbacks
    const revenueData = ensureArray(window.chartData.revenue);
    const hasRevenueData = revenueData.some(v => v > 0);

    const amountM = ensureArray(window.chartData.amountM);
    const orderedAmountM = ensureArray(window.chartData.orderedAmountM);
    const deliveredAmountM = ensureArray(window.chartData.deliveredAmountM);
    const canceledAmountM = ensureArray(window.chartData.canceledAmountM);
    const lastYearData = ensureArray(window.chartData.lastYearRevenue);

    const orderStatus = window.chartData.orderStatus && window.chartData.orderStatus.length ?
        window.chartData.orderStatus : sampleData.status;

    // 1. REVENUE CHART - Area Chart
    if (document.getElementById('revenue-chart')) {
        const options = {
            series: [{
                name: 'Chiffre d\'affaires',
                data: hasRevenueData ? revenueData : sampleData.revenue
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: true },
                fontFamily: 'inherit',
                animations: { enabled: true }
            },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#2377FC'],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val ? val.toLocaleString('fr-FR') + ' €' : '0 €';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val ? val.toLocaleString('fr-FR') + ' €' : '0 €';
                    }
                }
            },
            dataLabels: { enabled: false }
        };

        const chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();
        window.revenueChart = chart;
    }

    // 2. ORDER STATUS CHART - Donut
    if (document.getElementById('order-status-chart')) {
        const options = {
            series: orderStatus,
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'inherit'
            },
            labels: ['Livrées', 'Confirmées', 'En attente', 'Annulées'],
            colors: ['#078407', '#3b82f6', '#FFA500', '#FF0000'],
            legend: {
                show: true,
                position: 'bottom',
                fontSize: '12px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' commandes';
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#order-status-chart"), options);
        chart.render();
        window.orderStatusChart = chart;
    }

    // 3. REVENUE BY STATUS CHART - Bar Chart
    if (document.getElementById('line-chart-8')) {
        const hasBarData = amountM.some(v => v > 0) || orderedAmountM.some(v => v > 0) || deliveredAmountM.some(v => v > 0);

        const options = {
            series: [
                { name: 'Total', data: hasBarData ? amountM : sampleData.revenue },
                { name: 'En Attente', data: hasBarData ? orderedAmountM : sampleData.ordered },
                { name: 'Livré', data: hasBarData ? deliveredAmountM : sampleData.delivered },
                { name: 'Annulé', data: hasBarData ? canceledAmountM : sampleData.canceled }
            ],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadius: 6
                }
            },
            colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val ? val.toLocaleString('fr-FR') + ' €' : '0 €';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val ? val.toLocaleString('fr-FR') + ' €' : '0 €';
                    }
                }
            },
            legend: { position: 'top' }
        };

        const chart = new ApexCharts(document.querySelector("#line-chart-8"), options);
        chart.render();
        window.barChart = chart;
    }

    // 4. DAILY SALES CHART - Line Chart
    if (document.getElementById('daily-sales-chart')) {
        const options = {
            series: [
                { name: 'Cette semaine', data: [65, 78, 82, 89, 94, 102, 98] },
                { name: 'Semaine dernière', data: [45, 52, 58, 62, 71, 68, 75] }
            ],
            chart: {
                type: 'line',
                height: 280,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#8b5cf6', '#ec4899'],
            xaxis: {
                categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
            },
            markers: { size: 5 },
            legend: { position: 'top' },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val + ' €';
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#daily-sales-chart"), options);
        chart.render();
        window.dailySalesChart = chart;
    }

    // 5. CATEGORIES CHART - Pie Chart
    if (document.getElementById('categories-chart')) {
        const categories = window.chartData.topCategories || [];
        const hasCatData = categories.length > 0 && categories.some(c => (c.products_count || 0) > 0);

        const seriesData = hasCatData ?
            categories.map(c => parseInt(c.products_count) || 0) :
            [35, 25, 20, 15, 5];

        const labelsData = hasCatData ?
            categories.map(c => c.name || 'Catégorie') :
            ['Bijoux', 'Montres', 'Colliers', 'Bagues', 'Bracelets'];

        const colorsData = hasCatData ?
            categories.map(c => c.color || '#2377FC') :
            ['#2377FC', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'];

        const options = {
            series: seriesData,
            chart: {
                type: 'pie',
                height: 250,
                fontFamily: 'inherit'
            },
            labels: labelsData,
            colors: colorsData,
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' produits';
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#categories-chart"), options);
        chart.render();
        window.categoriesChart = chart;
    }

    // 6. ACQUISITION CHART - Radial Bar
    if (document.getElementById('acquisition-chart')) {
        const conversion = parseFloat(window.chartData.conversionRate) || 2.5;
        const repeat = parseFloat(window.chartData.repeatRate) || 15;
        const satisfaction = (parseFloat(window.chartData.avgRating) || 4) * 20;

        const options = {
            series: [conversion, repeat, satisfaction],
            chart: {
                type: 'radialBar',
                height: 250,
                fontFamily: 'inherit'
            },
            plotOptions: {
                radialBar: {
                    hollow: { size: '30%' },
                    dataLabels: {
                        show: true,
                        name: { fontSize: '12px' },
                        value: {
                            fontSize: '16px',
                            fontWeight: 600,
                            formatter: function(val) {
                                return Math.round(val) + '%';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Performance',
                            formatter: function() {
                                return Math.round((conversion + repeat + satisfaction) / 3) + '%';
                            }
                        }
                    }
                }
            },
            labels: ['Conversion', 'Fidélité', 'Satisfaction'],
            colors: ['#2377FC', '#10b981', '#f59e0b']
        };

        const chart = new ApexCharts(document.querySelector("#acquisition-chart"), options);
        chart.render();
        window.acquisitionChart = chart;
    }

    // 7. COMPARISON CHART - Bar Chart
    if (document.getElementById('comparison-chart')) {
        const currentYear = new Date().getFullYear();
        const hasComparisonData = revenueData.some(v => v > 0) || lastYearData.some(v => v > 0);

        const options = {
            series: [
                {
                    name: currentYear.toString(),
                    data: hasComparisonData && revenueData.some(v => v > 0) ? revenueData : sampleData.revenue
                },
                {
                    name: (currentYear - 1).toString(),
                    data: hasComparisonData && lastYearData.some(v => v > 0) ? lastYearData : sampleData.lastYear
                }
            ],
            chart: {
                type: 'bar',
                height: 280,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            colors: ['#2377FC', '#9ca3af'],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val ? val.toLocaleString('fr-FR') + ' €' : '0 €';
                    }
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '70%',
                    borderRadius: 4
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#comparison-chart"), options);
        chart.render();
        window.comparisonChart = chart;
    }

    // 8. STOCK CHART - Radial Bar
    if (document.getElementById('stock-chart')) {
        const total = parseInt(window.chartData.totalProducts) || 100;
        const inStock = parseInt(window.chartData.inStock) || 75;
        const stockPercent = total > 0 ? Math.round((inStock / total) * 100) : 75;

        const options = {
            series: [stockPercent],
            chart: {
                type: 'radialBar',
                height: 200,
                offsetY: -20,
                fontFamily: 'inherit',
                sparkline: { enabled: true }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: { size: '60%' },
                    track: { background: '#e7e7e7', strokeWidth: '97%' },
                    dataLabels: {
                        name: {
                            show: true,
                            fontSize: '12px',
                            offsetY: -10
                        },
                        value: {
                            fontSize: '24px',
                            fontWeight: 600,
                            offsetY: 5,
                            formatter: function(val) {
                                return val + '%';
                            }
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#10b981'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            colors: ['#2377FC'],
            labels: ['Stock disponible']
        };

        const chart = new ApexCharts(document.querySelector("#stock-chart"), options);
        chart.render();
        window.stockChart = chart;
    }

    // SPARKLINES - Simplified version
    const sparklineElements = {
        'sparkline-revenue': { color: '#2377FC', data: [12500, 15200, 11800, 19500, 22300, 26800, 24100] },
        'sparkline-orders': { color: '#078407', data: [120, 145, 110, 180, 210, 245, 220] },
        'sparkline-customers': { color: '#3b82f6', data: [5, 8, 12, 15, 18, 22, 25] },
        'sparkline-avg': { color: '#FFA500', data: [45, 52, 48, 55, 58, 62, 65] }
    };

    Object.keys(sparklineElements).forEach(function(id) {
        if (document.getElementById(id)) {
            const el = sparklineElements[id];
            const options = {
                series: [{ data: el.data }],
                chart: {
                    type: 'area',
                    height: 30,
                    sparkline: { enabled: true },
                    animations: { enabled: true }
                },
                stroke: { curve: 'smooth', width: 2 },
                colors: [el.color],
                fill: { opacity: 0.3 },
                tooltip: { enabled: false }
            };

            try {
                const chart = new ApexCharts(document.querySelector("#" + id), options);
                chart.render();
            } catch(e) {
                console.error('Error rendering sparkline ' + id + ':', e);
            }
        }
    });

    console.log('All charts initialized successfully!');
});

// Global functions
window.downloadChart = function(chartId, filename) {
    const chart = window[chartId] || window[chartId.replace('-', '')];
    if (chart && typeof chart.dataURI === 'function') {
        chart.dataURI().then(function(uri) {
            const link = document.createElement('a');
            link.href = uri.imgURI;
            link.download = filename + '-' + new Date().getTime() + '.png';
            link.click();
        }).catch(function(err) {
            console.error('Error downloading chart:', err);
        });
    }
};

window.expandChart = function(chartId) {
    const container = document.getElementById(chartId);
    if (container) {
        container.classList.toggle('expanded');
        container.style.height = container.classList.contains('expanded') ? '500px' : '350px';
        window.dispatchEvent(new Event('resize'));
    }
};
</script>
@endpush
