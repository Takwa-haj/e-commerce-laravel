@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Email Management</h1>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#testEmailModal">
                    <i class="fas fa-paper-plane"></i> Test Email Configuration
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
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
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                                Newsletter Subscribers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubscribers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
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
                                Email Templates</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">6</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Actions -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Send Custom Email</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.emails.send-custom') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="recipient_type">Recipient Type</label>
                            <select class="form-control" name="recipient_type" id="recipient_type" required>
                                <option value="">Select Recipient Type</option>
                                <option value="all_users">All Registered Users</option>
                                <option value="specific_users">Specific Users</option>
                                <option value="newsletter_subscribers">Newsletter Subscribers</option>
                                <option value="order_customers">All Order Customers</option>
                            </select>
                        </div>

                        <div class="form-group" id="specific_users_container" style="display: none;">
                            <label for="recipients">Select Users (Email addresses)</label>
                            <select class="form-control select2" name="recipients[]" id="recipients" multiple>
                                @foreach(\App\Models\User::select('email', 'name')->get() as $user)
                                    <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message Content</label>
                            <textarea class="form-control" name="message" id="message" rows="10" required></textarea>
                            <small class="form-text text-muted">HTML is supported. Use {{ '{name}' }} to insert recipient's name.</small>
                        </div>

                        <div class="form-group">
                            <label for="template">Template</label>
                            <select class="form-control" name="template" id="template">
                                <option value="default">Default Template</option>
                                <option value="newsletter">Newsletter Template</option>
                                <option value="promotional">Promotional Template</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="schedule">Schedule (Optional)</label>
                            <input type="datetime-local" class="form-control" name="schedule" id="schedule">
                            <small class="form-text text-muted">Leave empty to send immediately</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Email
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.emails.templates') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt mr-2"></i>
                                Email Templates
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="{{ route('admin.emails.statistics') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-line mr-2"></i>
                                Email Statistics
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Send Order Emails
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-toggle="modal" data-target="#testEmailModal">
                            <div>
                                <i class="fas fa-paper-plane mr-2"></i>
                                Test Email Configuration
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Email Logs -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Email Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="emailLogsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Recipient</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentLogs) && count($recentLogs) > 0)
                                    @foreach($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $log->recipient_type }}</td>
                                        <td>{{ Str::limit($log->subject, 30) }}</td>
                                        <td>
                                            <span class="badge badge-success">Sent</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No email logs found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Email Configuration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.emails.test') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="test_email">Test Email Address</label>
                        <input type="email" class="form-control" name="test_email" id="test_email" required>
                        <small class="form-text text-muted">Enter an email address to send a test email.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Test Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select users",
            allowClear: true
        });

        // Show/hide specific users field
        $('#recipient_type').change(function() {
            if ($(this).val() === 'specific_users') {
                $('#specific_users_container').show();
            } else {
                $('#specific_users_container').hide();
            }
        });
    });
</script>
@endsection
