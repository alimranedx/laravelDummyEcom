@extends('admin.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Filter card */
    .filter-card { background:#fff; border-radius:1rem; box-shadow:0 2px 20px rgba(0,0,0,.06); }
    .filter-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#6c757d; display:block; margin-bottom:.4rem; }
    .filter-input-group { background:#f8f9fa; border-radius:.6rem; overflow:hidden; display:flex; align-items:center; }
    .filter-input-group .fi-icon { padding:.55rem .85rem; color:#adb5bd; font-size:1rem; }
    .filter-input-group input,
    .filter-input-group select { background:#f8f9fa; border:none; box-shadow:none; outline:none; flex:1; padding:.55rem .5rem; font-size:.9rem; }
    .filter-input-group input:focus,
    .filter-input-group select:focus { box-shadow:none; background:#f1f3f9; }
    .btn-apply { background:linear-gradient(135deg,#4f46e5,#7c3aed); border:none; color:#fff; font-weight:600; border-radius:.65rem; }
    .btn-apply:hover { opacity:.9; color:#fff; }
    .btn-reset { background:#f8f9fa; border:none; color:#6c757d; font-weight:600; border-radius:.65rem; }
    .btn-reset:hover { background:#e9ecef; }
    .active-badge { font-size:.73rem; background:rgba(79,70,229,.1); color:#4f46e5; border-radius:.5rem; padding:.25rem .75rem; font-weight:600; }

    /* Table */
    .letter-spacing-1 { letter-spacing:1px; }
    .transition-row { transition:background-color .2s; }
    .transition-row:hover { background-color:rgba(248,249,250,.5); }
    .pagination { margin-bottom:0; }
    .page-link { border:none; padding:.5rem .85rem; margin:0 2px; border-radius:8px !important; color:#6c757d; }
    .page-item.active .page-link { background-color:#4f46e5; box-shadow:0 4px 10px rgba(79,70,229,.2); }

    /* Flatpickr */
    .flatpickr-calendar { border-radius:1rem; box-shadow:0 20px 60px rgba(0,0,0,.12); border:none; }
    .flatpickr-day.selected,.flatpickr-day.startRange,.flatpickr-day.endRange { background:#4f46e5!important; border-color:#4f46e5!important; }
    .flatpickr-day.inRange { background:rgba(79,70,229,.1)!important; border-color:transparent!important; box-shadow:none!important; }
    .flatpickr-months .flatpickr-month { background:#4f46e5; border-radius:1rem 1rem 0 0; }
    .flatpickr-current-month, .flatpickr-weekdays { color:#fff!important; fill:#fff!important; }
    .flatpickr-weekday { color:rgba(255,255,255,.7)!important; }
    .flatpickr-monthDropdown-months { color:#fff!important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 animate-fade-in">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Sale Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Sale Report</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border-left:4px solid #4f46e5!important">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-4 me-4">
                        <i class="bi bi-currency-dollar fs-2 text-primary"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-bold text-uppercase letter-spacing-1 mb-1">Total Revenue</span>
                        <span class="h2 fw-bold mb-0 text-dark">${{ number_format($summary['total_revenue'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border-left:4px solid #10b981!important">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-4 rounded-4 me-4">
                        <i class="bi bi-cart-check fs-2 text-success"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-bold text-uppercase letter-spacing-1 mb-1">Total Orders</span>
                        <span class="h2 fw-bold mb-0 text-dark">{{ $summary['total_orders'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card with Filter inside header --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">

        {{-- Filter Row --}}
        <div class="card-header bg-white border-bottom py-4 px-4">
            <form method="GET" action="{{ route('admin.sale-report.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">

                    {{-- Date Range --}}
                    <div class="col-lg-5 col-md-6">
                        <span class="filter-label"><i class="bi bi-calendar-range me-1"></i>Date Range</span>
                        <div class="filter-input-group">
                            <span class="fi-icon"><i class="bi bi-calendar3"></i></span>
                            <input type="text" id="dateRangePicker" name="date_range"
                                placeholder="Pick a date range…"
                                value="{{ request('date_range') }}"
                                autocomplete="off" readonly>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-3 col-md-4">
                        <span class="filter-label"><i class="bi bi-funnel me-1"></i>Status</span>
                        <div class="filter-input-group">
                            <span class="fi-icon"><i class="bi bi-tag"></i></span>
                            <select name="status">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-4 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-apply px-4 py-2 flex-grow-1">
                            <i class="bi bi-search me-1"></i> Apply
                        </button>
                        @if(request()->hasAny(['date_range','status']))
                            <a href="{{ route('admin.sale-report.index') }}" class="btn btn-reset px-3 py-2" title="Clear filters">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>

                </div>

                {{-- Active filter tags --}}
                @if(request()->hasAny(['date_range','status']))
                <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted small">Filters:</span>
                    @if(request('date_range'))
                        <span class="active-badge"><i class="bi bi-calendar3 me-1"></i>{{ request('date_range') }}</span>
                    @endif
                    @if(request('status'))
                        <span class="active-badge"><i class="bi bi-tag me-1"></i>{{ ucfirst(request('status')) }}</span>
                    @endif
                    <span class="text-muted small">— {{ $summary['total_orders'] }} result(s)</span>
                </div>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4 py-3 border-0">Order ID</th>
                        <th class="px-4 py-3 border-0">Customer</th>
                        <th class="px-4 py-3 border-0">Amount</th>
                        <th class="px-4 py-3 border-0">Status</th>
                        <th class="px-4 py-3 border-0">Date</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($sales as $sale)
                        <tr class="transition-row">
                            <td class="px-4 py-4 fw-bold text-primary small">#{{ $sale->id }}</td>
                            <td class="px-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-2 bg-light text-dark fw-bold border d-flex align-items-center justify-content-center rounded-circle"
                                        style="width:32px;height:32px;font-size:.75rem">
                                        {{ strtoupper(substr($sale->user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-medium text-dark">{{ $sale->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 fw-bold text-dark">${{ number_format($sale->total_price, 2) }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $sv = $sale->status->value;
                                    $sc = $sv === 'completed' ? 'bg-success' : ($sv === 'pending' ? 'bg-warning text-dark' : 'bg-secondary');
                                @endphp
                                <span class="badge rounded-pill {{ $sc }} px-3 py-2 small fw-normal">{{ $sale->status->label() }}</span>
                            </td>
                            <td class="px-4 py-4 text-muted small">{{ $sale->created_at->format('M d, Y — H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-1 opacity-25 d-block mb-3"></i>
                                <strong>No sales data found</strong>
                                <p class="small mt-1">Try adjusting the filters above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="card-footer bg-white py-4 px-4 border-top">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fp = flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        showMonths: window.innerWidth >= 768 ? 2 : 1,
        allowInput: false,
        disableMobile: true,
        onClose: function(selectedDates, dateStr, instance) {
            // If only one date selected (range incomplete), clear
            if (selectedDates.length === 1) {
                instance.clear();
            }
        }
    });
});
</script>
@endpush
