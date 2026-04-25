@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Email Statistics</h1>
                <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Back to Email Management
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Emails Sent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSent }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sent Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sentToday }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sent This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sentThisMonth }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Email Activity (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="emailActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Email Types</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="emailTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Email Logs</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="emailLogsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Recipient Type</th>
                                    <th>Subject</th>
                                    <th>Recipients</th>
                                    <th>Sent By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\EmailLog::orderBy('created_at', 'desc')->take(50)->get() as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @php
                                            $types = [
                                                'all_users' => 'All Users',
                                                'specific_users' => 'Specific Users',
                                                'newsletter_subscribers' => 'Newsletter Subscribers',
                                                'order_customers' => 'Order Customers',
                                                'test' => 'Test Email'
                                            ];
                                        @endphp
                                        {{ $types[$log->recipient_type] ?? $log->recipient_type }}
                                    </td>
                                    <td>{{ Str::limit($log->subject, 30) }}</td>
                                    <td>{{ $log->recipient_count }}</td>
                                    <td>{{ $log->sender->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge badge-success">Sent</span>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Email Activity Chart
        var ctx = document.getElementById('emailActivityChart').getContext('2d');
        var emailActivityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Emails Sent',
                    data: [65, 59, 80, 81, 56, 55, 40, 70, 85, 90, 75, 60],
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) {
                                return value;
                            }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            drawBorder: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 14
                        },
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' emails';
                            }
                        }
                    }
                }
            }
        });

        // Email Types Chart
        var ctx2 = document.getElementById('emailTypesChart').getContext('2d');
        var emailTypesChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Order Confirmations', 'Status Updates', 'Newsletters', 'Promotional', 'Test'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + '%';
                                return label;
                            }
                        }
                    }
                },
                cutout: '70%',
            },
        });

        // Initialize DataTable
        $('#emailLogsTable').DataTable({
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "Search logs:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });
</script>
@endsection
