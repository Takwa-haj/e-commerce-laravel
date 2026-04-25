<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href="{{ asset('css/dynamic-colors.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <!-- ApexCharts CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bijoux') }}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Boutique de Bijoux  Petits Prix & Grande Qualité" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/global.css') }}">


    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">

    <!-- Favicon -->
    @if(isset($settings) && $settings->favicon)
        <link rel="icon" href="{{ asset('uploads/settings/' . $settings->favicon) }}" type="image/x-icon">
        <link rel="apple-touch-icon-precomposed" href="{{ asset('uploads/settings/' . $settings->favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon-precomposed" href="{{ asset('images/logo.png') }}">
    @endif
@php
    $settings = \App\Models\Setting::first();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $settings->theme ?? 'default' }}">

</html>
    <style>
        /* Batch selection styles */
        .batch-mode .order-checkbox {
            display: table-cell !important;
            vertical-align: middle;
        }

        .order-checkbox {
            display: none;
        }

        .batch-actions {
            transition: all 0.3s ease;
        }

        .batch-actions .alert {
            border-radius: 8px;
            margin-bottom: 0;
        }

        /* Print button floating */
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        @media print {
            .print-btn {
                display: none !important;
            }
        }

        /* Status badges */
        .status-badge {
            font-size: 11px;
            padding: 3px 8px;
            font-weight: 500;
            text-transform: uppercase;
        }

        /* Product images in orders */
        .product-image-small {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        /* Quick actions dropdown */
        .quick-actions .dropdown-menu {
            min-width: 150px;
        }

        .quick-actions .dropdown-item {
            padding: 6px 15px;
            font-size: 13px;
        }

        .quick-actions .dropdown-item i {
            width: 18px;
            text-align: center;
        }

        .admin-logo img {
            max-width: 180px !important;
            max-height: 60px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
        }

        /* Print Styles */
        @media print {
            .section-menu-left,
            .header-dashboard,
            .breadcrumbs,
            .bottom-page,
            .tf-button,
            .dropdown,
            .pagination,
            .wg-box .flex:first-child,
            .modal,
            .alert {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
                font-size: 11pt !important;
                padding: 20px !important;
            }

            #wrapper,
            #page,
            .section-content-right,
            .main-content-inner,
            .main-content-wrap,
            .main-content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .wg-box {
                border: none !important;
                box-shadow: none !important;
                margin: 0 0 20px 0 !important;
                padding: 0 !important;
                page-break-inside: avoid;
            }

            .table {
                border-collapse: collapse !important;
                width: 100% !important;
                font-size: 10pt !important;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                background: white !important;
                color: black !important;
            }

            .table th {
                background-color: #f0f0f0 !important;
                font-weight: bold !important;
            }

            h1, h2, h3, h4, h5, h6 {
                color: black !important;
                page-break-after: avoid;
            }

            a {
                color: black !important;
                text-decoration: none !important;
            }

            .badge {
                border: 1px solid #000 !important;
                background: white !important;
                color: black !important;
                padding: 2px 6px !important;
                font-weight: bold !important;
            }

            img {
                max-width: 100% !important;
            }

            .divider {
                display: none !important;
            }

            /* Page breaks */
            .page-break {
                page-break-before: always;
            }

            /* No break inside important sections */
            .no-break {
                page-break-inside: avoid;
            }

            @page {
                margin: 2cm;
                size: A4;
            }

            @page :first {
                margin-top: 1cm;
            }
        }

        /* Quick Print Button */
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        @media print {
            .print-btn {
                display: none !important;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            display: none;
        }

        .loading-overlay.active {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-spinner {
            background: white;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification-badge {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>

    @stack("styles")
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">
                <!-- Sidebar Menu -->
                <div class="section-menu-left">
                    <div class="box-logo logo admin-logo">
                        <a href="{{ route('admin.index') }}">
                            @if(isset($settings) && $settings->logo)
                                <img src="{{ asset('uploads/settings/' . $settings->logo) }}" alt="{{ $settings->site_name ?? 'Bijoux' }}" class="logo__image d-block" />
                            @else
                                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ $settings->site_name ?? 'Bijoux' }}" class="logo__image d-block" />
                            @endif
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Accueil Principal</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.index') }}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Tableau de Bord</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">
                                <!-- Products Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                                        <div class="text">Produits</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.product.add') }}" class="">
                                                <div class="text">Ajouter un Produit</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.products') }}" class="">
                                                <div class="text">Produits</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Sizes Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="fas fa-vector-square fa-fw me-3"></i></div>
                                        <div class="text">Tailles</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.size.add') }}" class="">
                                                <div class="text">Nouvelle Taille</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.sizes') }}" class="">
                                                <div class="text">Tailles</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Colors Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="fas fa-palette fa-fw me-3"></i></div>
                                        <div class="text">Couleurs</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.color.add') }}" class="">
                                                <div class="text">Nouvelle Couleur</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.colors') }}" class="">
                                                <div class="text">Couleurs</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Brand Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Marque</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brand.add') }}" class="">
                                                <div class="text">Nouvelle Marque</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brands') }}" class="">
                                                <div class="text">Marques</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Category Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Catégorie</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.category.add') }}" class="">
                                                <div class="text">Nouvelle Catégorie</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.categories') }}" class="">
                                                <div class="text">Catégories</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
<!-- Color Palettes -->
<li class="menu-item">
    <a href="{{ route('admin.color-palettes') }}" class="">
        <div class="icon"><i class="fas fa-palette fa-fw me-3"></i></div>
        <div class="text">Palettes de Couleurs</div>
    </a>

</li>
                                <!-- Orders Menu -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-file-plus"></i></div>
                                        <div class="text">Commandes</div>
                                        @php
                                            $newOrdersCount = \App\Services\NotificationService::getUnreadOrderCount();
                                        @endphp
                                        @if($newOrdersCount > 0)
                                            <span class="notification-badge">{{ $newOrdersCount }}</span>
                                        @endif
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.orders') }}" class="">
                                                <div class="text">Commandes
                                                    @if($newOrdersCount > 0)
                                                        <span class="badge bg-danger ms-1">{{ $newOrdersCount }}</span>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="#" class="">
                                                <div class="text">Suivi de commande</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Refunds -->
                                <li class="menu-item">
                                    <a class="@if(request()->routeIs('admin.refunds')) active @endif"
                                       href="{{ route('admin.refunds') }}">
                                        <div class="icon"><i class="icon-file-plus"></i></div>
                                        <div class="text">Refunds</div>
                                        @php
                                            $pendingRefundsCount = \App\Services\NotificationService::getPendingRefundCount();
                                        @endphp
                                        @if($pendingRefundsCount > 0)
                                            <span class="notification-badge">{{ $pendingRefundsCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <!-- Reviews -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.reviews') }}" class="">
                                        <div class="icon"><i class="icon-star"></i></div>
                                        <div class="text">Reviews</div>
                                        @php
                                            $pendingReviewsCount = \App\Services\NotificationService::getPendingReviewCount();
                                        @endphp
                                        @if($pendingReviewsCount > 0)
                                            <span class="notification-badge">{{ $pendingReviewsCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <!-- Category Banners -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.category-banners') }}" class="">
                                        <div class="icon"><i class="icon-image"></i></div>
                                        <div class="text">Category Banners</div>
                                    </a>
                                </li>
                                    <!-- Top Banner Management -->
                                    <li class="menu-item">
                                        <a href="{{ route('admin.top-banners.index') }}" class="">
                                            <div class="icon"><i class="fas fa-bullhorn"></i></div>
                                            <div class="text">Top Banner</div>
                                        </a>
                                    </li>
                                <!-- Home Page Slides -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.slides') }}" class="">
                                        <div class="icon"><i class="icon-image"></i></div>
                                        <div class="text">Home Page Slides</div>
                                    </a>
                                </li>

                                <!-- Shop Banners -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.shop-banners') }}" class="">
                                        <div class="icon"><i class="icon-shopping-bag"></i></div>
                                        <div class="text">Shop Banners</div>
                                    </a>
                                </li>

<!-- Slides & Banners Management -->
<li class="menu-item has-children">
    <a href="javascript:void(0);" class="menu-item-button">
        <div class="icon"><i class="fas fa-images"></i></div>
        <div class="text">Slides & Bannières</div>
    </a>
    <ul class="sub-menu">
        <li class="sub-menu-item">
            <a href="{{ route('admin.slides.manage') }}" class="">
                <div class="text">Tous les Slides & Bannières</div>
            </a>
        </li>
        <li class="sub-menu-item">
            <a href="{{ route('admin.slides') }}" class="">
                <div class="text">Slides Accueil</div>
            </a>
        </li>
        <li class="sub-menu-item">
            <a href="{{ route('admin.shop-banners') }}" class="">
                <div class="text">Bannières Boutique</div>
            </a>
        </li>
        <li class="sub-menu-item">
            <a href="{{ route('admin.category-banners') }}" class="">
                <div class="text">Bannières Catégories</div>
            </a>
        </li>
    </ul>
</li>
                                <!-- Coupons -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.coupons') }}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Coupons</div>
                                    </a>
                                </li>
                                <!-- Blog Menu -->
                                <li class="menu-item">
                                    <a  class="nav-link"  href="{{ route('admin.blogs') }}" class="@if(request()->routeIs('admin.blogs')) active @endif">
                                        <div class="icon"><i class="icon-file-text"></i></div>
                                        <div class="text">Blog</div>
                                    </a>
                                </li>
                                <!-- In your admin sidebar -->
                                <li class="menu-item">
                                    <a  href="{{ route('admin.emails.index') }}" class="">
                                  <div class="icon"><i class="fas fa-envelope"></i></div>
                                    <div class="text">Email Management</div>
                                    </a>
                                </li>

                                <!-- Messages -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.contacts') }}" class="">
                                        <div class="icon"><i class="icon-mail"></i></div>
                                        <div class="text">Messages</div>
                                        @php
                                            $newMessagesCount = \App\Services\NotificationService::getUnreadContactCount();
                                        @endphp
                                        @if($newMessagesCount > 0)
                                            <span class="notification-badge">{{ $newMessagesCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <!-- Users -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.users') }}" class="">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <div class="text">Utilisateurs</div>
                                    </a>
                                </li>

                                <!-- Settings -->
                                <li class="menu-item">
                                    <a href="{{ route('admin.settings') }}" class="">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">Paramètres</div>
                                    </a>
                                </li>

                                <!-- Logout -->
                                <li class="menu-item">
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a href="javascript:void(0);" class="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <div class="icon"><i class="fas fa-sign-out-alt fa-fw me-3"></i></div>
                                            <div class="text">Se Déconnecter</div>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="section-content-right">
                    <!-- Header -->
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="{{ route('admin.index') }}">
                                    @if(isset($settings) && $settings->logo)
                                        <img class="" id="logo_header_mobile" alt="{{ $settings->site_name ?? 'Bijoux' }}"
                                             src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                             data-light="{{ asset('uploads/settings/' . $settings->logo) }}"
                                             data-dark="{{ asset('uploads/settings/' . $settings->logo) }}"
                                             data-width="154px" data-height="52px"
                                             data-retina="{{ asset('uploads/settings/' . $settings->logo) }}">
                                    @else
                                        <img class="" id="logo_header_mobile" alt="{{ $settings->site_name ?? 'Bijoux' }}"
                                             src="{{ asset('images/logo/logo.png') }}"
                                             data-light="{{ asset('images/logo/logo.png') }}"
                                             data-dark="{{ asset('images/logo/logo.png') }}"
                                             data-width="154px" data-height="52px"
                                             data-retina="{{ asset('images/logo/logo.png') }}">
                                    @endif
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>

                                <!-- Search Form -->
                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Rechercher..." class="show-search" name="name" id="search-input" tabindex="2" value="" aria-required="true" required autocomplete="off">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                    <div class="box-content-search">
                                        <ul id="box-content-search"></ul>
                                    </div>
                                </form>
                            </div>

                            <div class="header-grid">
                                <!-- Notifications Dropdown -->
                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="badge bg-danger notification-count" id="notification-count">
                                                    {{ \App\Services\NotificationService::getUnreadCount() }}
                                                </span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2" style="min-width: 350px;">
                                            <li class="dropdown-header">
                                                <h6 class="mb-0">Notifications</h6>
                                                <a href="javascript:void(0);" class="mark-all-read text-small"
                                                   onclick="markAllAsRead()">Marquer tout comme lu</a>
                                            </li>
                                            <div id="notifications-list" class="dropdown-notifications">
                                                @php
                                                    $notifications = \App\Services\NotificationService::getAdminNotifications(5);
                                                @endphp
                                                @include('admin.partials.notifications-list', ['notifications' => $notifications])
                                            </div>
                                        </ul>
                                    </div>
                                </div>

                                <!-- User Dropdown -->
                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <!-- User avatar would go here -->
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">
                                                        {{ Auth::user()->utype == 'ADM' ? 'Administrateur' : 'Utilisateur' }}
                                                    </span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.settings') }}" class="user-item">
                                                    <div class="icon"><i class="icon-user"></i></div>
                                                    <div class="body-title-2">Mon Compte</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon"><i class="icon-mail"></i></div>
                                                    <div class="body-title-2">Messages</div>
                                                    <div class="number">0</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.index') }}" class="user-item">
                                                    <div class="icon"><i class="icon-file-text"></i></div>
                                                    <div class="body-title-2">Tableau de Bord</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.settings') }}" class="user-item">
                                                    <div class="icon"><i class="icon-settings"></i></div>
                                                    <div class="body-title-2">Paramètres</div>
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}" id="logout-form-header">
                                                    @csrf
                                                    <a href="javascript:void(0);" class="user-item" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                                        <div class="icon"><i class="fas fa-sign-out-alt fa-fw me-3"></i></div>
                                                        <div class="body-title-2">Déconnexion</div>
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="main-content">
                        @yield("content")

                        <!-- Footer -->
                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 Elegant</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
    // Global loading state handler
    document.addEventListener('alpine:init', () => {
        Alpine.store('loading', {
            isLoading: false,
            message: 'Chargement...',

            start(message = 'Chargement...') {
                this.isLoading = true;
                this.message = message;
            },

            stop() {
                this.isLoading = false;
            }
        });
    });

    // Form submission handler
    window.handleFormSubmit = async (form, callback) => {
        const loadingStore = Alpine.store('loading');

        try {
            loadingStore.start('Traitement en cours...');

            const response = await fetch(form.action, {
                method: form.method,
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (callback) {
                callback(data, response);
            }

        } catch (error) {
            console.error('Form submission error:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
        } finally {
            loadingStore.stop();
        }
    };

    // Search functionality
    $(document).ready(function() {
        $("#search-input").on("keyup", function() {
            var searchQuery = $(this).val();
            if (searchQuery.length > 2) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.rechercher') }}",
                    data: {query: searchQuery},
                    dataType: 'json',
                    success: function(data) {
                        $("#box-content-search").html('');
                        $.each(data, function(index, item) {
                            var url = "{{ route('admin.product.edit', ':id') }}";
                            url = url.replace(':id', item.id);

                            $("#box-content-search").append(`
                                <li>
                                    <ul>
                                        <li class="product-item gap14 mb-10">
                                            <div class="image no-bg">
                                                <img src="{{ asset('uploads/products/thumbnails') }}/${item.image}" alt="${item.name}">
                                            </div>
                                            <div class="flex items-center justify-between gap20 flex-grow">
                                                <div class="name">
                                                    <a href="${url}" class="body-text">${item.name}</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-10">
                                            <div class="divider"></div>
                                        </li>
                                    </ul>
                                </li>
                            `);
                        });
                    }
                });
            }
        });

        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    // Notification functions
    function updateNotifications() {
        $.ajax({
            url: '{{ route("admin.notifications.load") }}',
            method: 'GET',
            success: function(response) {
                if (response.html) {
                    $('#notifications-list').html(response.html);
                    $('#notification-count').text(response.count);
                }
            }
        });
    }

    function markNotificationAsRead(notificationId) {
        $.ajax({
            url: '{{ route("admin.notification.mark-read") }}',
            method: 'POST',
            data: {
                notification_id: notificationId
            },
            success: function(response) {
                if (response.success) {
                    updateNotifications();
                }
            }
        });
    }

    function markAllAsRead() {
        $.ajax({
            url: '{{ route("admin.notification.mark-all-read") }}',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    updateNotifications();
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Toutes les notifications ont été marquées comme lues',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Impossible de marquer toutes les notifications comme lues'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.'
                });
            }
        });
    }

    // Auto-update notifications every 60 seconds
    setInterval(updateNotifications, 60000);

    // Update notifications on page load
    $(document).ready(function() {
        updateNotifications();
    });

    // Print functionality
    function printDocument(url) {
        var printWindow = window.open(url, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    function emailInvoice(orderId) {
        Swal.fire({
            title: 'Envoyer la facture par email',
            text: 'La facture sera envoyée au client par email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return fetch(`/admin/order/${orderId}/email-invoice`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Erreur lors de l\'envoi');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Envoyé!',
                    'La facture a été envoyée par email avec succès.',
                    'success'
                );
            }
        });
    }

    // Quick print buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Add print button to order details page
        if (document.querySelector('.order-details-page')) {
            const printBtn = document.createElement('button');
            printBtn.className = 'print-btn';
            printBtn.innerHTML = '<i class="fas fa-print"></i>';
            printBtn.onclick = function() {
                window.print();
            };
            document.body.appendChild(printBtn);
        }

        // Batch print selection
        const batchPrintBtn = document.getElementById('batch-print-btn');
        if (batchPrintBtn) {
            batchPrintBtn.addEventListener('click', function() {
                const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'))
                    .map(checkbox => checkbox.value);

                if (selectedOrders.length === 0) {
                    Swal.fire('Erreur', 'Veuillez sélectionner au moins une commande.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Imprimer en lot',
                    html: `
                        <p>Sélectionnez le type de document à imprimer:</p>
                        <select id="document-type" class="form-control mt-2">
                            <option value="invoice">Factures</option>
                            <option value="shipping_label">Étiquettes d'expédition</option>
                            <option value="packing_slip">Bordereaux de colisage</option>
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Imprimer',
                    cancelButtonText: 'Annuler',
                    preConfirm: function() {
                        const documentType = document.getElementById('document-type').value;

                        // Submit form for batch print
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("admin.order.batch.print.summaries") }}';
                        form.target = '_blank';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                        form.appendChild(csrfToken);

                        selectedOrders.forEach(orderId => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'order_ids[]';
                            input.value = orderId;
                            form.appendChild(input);
                        });

                        const documentTypeInput = document.createElement('input');
                        documentTypeInput.type = 'hidden';
                        documentTypeInput.name = 'document_type';
                        documentTypeInput.value = documentType;
                        form.appendChild(documentTypeInput);

                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);
                    }
                });
            });
        }
    });
    </script>

    <!-- Loading Overlay Template -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span id="loading-message">Chargement...</span>
        </div>
    </div>

    @stack("scripts")
</body>
</html>
