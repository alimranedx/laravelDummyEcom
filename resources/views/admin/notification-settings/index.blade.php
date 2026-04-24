@extends('admin.layout')

@section('content')
<div class="container-fluid py-4 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Notification Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Notification Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Manage Live Notifications</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.notification-management.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" id="sale_notification_enabled" name="sale_notification_enabled" {{ $saleNotificationEnabled ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="sale_notification_enabled">Sale Notification (New Orders)</label>
                            </div>
                            <div class="form-text ms-1 mt-2 text-muted">Enable this to receive live popup notifications when a new order is placed from anywhere.</div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="mb-4">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" id="user_registered_notification_enabled" name="user_registered_notification_enabled" {{ $userRegisteredNotificationEnabled ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="user_registered_notification_enabled">User Registration Notification</label>
                            </div>
                            <div class="form-text ms-1 mt-2 text-muted">Enable this to receive live popup notifications when a new user registers on the platform.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <button class="btn btn-primary px-4 py-2" type="submit">
                                <i class="bi bi-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
