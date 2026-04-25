@extends('admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/assets/bootstrap-select-1.14.0-beta3/css/bootstrap-select.min.css') }}">
    <style>
        /* Filter Premium UI */
        .filter-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
        }

        .filter-label {
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            display: block;
            margin-bottom: .6rem;
        }

        .filter-input-group {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            height: 50px;
        }

        .filter-input-group:focus-within {
            background: #fff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .filter-input-group .fi-icon {
            padding: 0 1rem;
            color: #64748b;
            font-size: 1.1rem;
            border-right: 1px solid #e2e8f0;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .filter-input-group input {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            flex: 1;
            padding: 0 1rem;
            font-size: .95rem;
            color: #1e293b;
            font-weight: 500;
        }

        .py-2-5 {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        .btn-apply {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: .75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-apply:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
            color: #fff;
        }

        .btn-reset {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-weight: 600;
            border-radius: .75rem;
            height: 50px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #ef4444;
        }

        .active-badge {
            font-size: .8rem;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            border-radius: 2rem;
            padding: .35rem 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        /* Selectpicker Integration Overrides */
        .bootstrap-select .dropdown-toggle {
            background: transparent !important;
            border: none !important;
            height: 48px !important;
            padding: 0 1rem !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: none !important;
            color: #1e293b !important;
            font-weight: 500 !important;
            font-size: 0.95rem !important;
        }

        .bootstrap-select .dropdown-menu {
            border-radius: 1rem !important;
            margin-top: 8px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #e2e8f0 !important;
        }
        
        .page-item.active .page-link { background-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
        .pagination-wrapper p.small.text-muted { display: none !important; }
        .pagination-wrapper nav>div.d-sm-flex { justify-content: flex-end !important; }
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
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
                            <span class="text-muted small d-block fw-bold text-uppercase letter-spacing-1 mb-1">Total
                                Revenue</span>
                            <span
                                class="h2 fw-bold mb-0 text-dark">${{ number_format($summary['total_revenue'], 2) }}</span>
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
                            <span class="text-muted small d-block fw-bold text-uppercase letter-spacing-1 mb-1">Total
                                Orders</span>
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
                    <div class="row g-3 align-items-end justify-content-end">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Date Range</label>
                            <input class="form-control rounded-pill border-light bg-light py-2 px-4 shadow-sm" type="text" name="date_range" id="date_range" value="{{ request('date_range') }}" placeholder="YYYY/MM/DD - YYYY/MM/DD"/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Payment ID</label>
                            <input class="form-control rounded-pill border-light bg-light py-2 px-4 shadow-sm" type="text" name="payment_id" value="{{ request('payment_id') }}" placeholder="PAY-XXXX..."/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Search Keyword</label>
                            <input class="form-control rounded-pill border-light bg-light py-2 px-4 shadow-sm" type="text" name="q" value="{{ request('q') }}" placeholder="ID, Name..."/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Status Filter</label>
                            <select name="status[]" id="status_filter" class="selectpicker form-control" multiple data-actions-box="true" title="All Statuses">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}" {{ in_array($status->value, (array) request('status')) ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm py-2">Filter</button>
                            @if (request()->anyFilled(['date_range', 'status', 'q', 'payment_id']))
                                <a href="{{ route('admin.sale-report.index') }}" class="btn btn-light rounded-pill border w-100 fw-bold text-center py-2 d-flex align-items-center justify-content-center">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">Order ID</th>
                            <th class="px-4 py-3 border-0">Payment ID</th>
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
                                <td class="px-4 py-4 small text-muted fw-medium">{{ $sale->payment_id }}</td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 bg-light text-dark fw-bold border d-flex align-items-center justify-content-center rounded-circle"
                                            style="width:32px;height:32px;font-size:.75rem">
                                            {{ !empty($sale->user->name) ? strtoupper(substr($sale->user->name, 0, 1)) : 'Guest' }}
                                        </div>
                                        <span
                                            class="fw-medium text-dark">{{ !empty($sale->user->name) ? $sale->user->name : 'Guest' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">${{ number_format($sale->total_price, 2) }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $sv = $sale->status->value;
                                        $sc =
                                            $sv === 'completed'
                                                ? 'bg-success'
                                                : ($sv === 'pending'
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-secondary');
                                    @endphp
                                    <span
                                        class="badge rounded-pill {{ $sc }} px-3 py-2 small fw-normal">{{ $sale->status->label() }}</span>
                                </td>
                                <td class="px-4 py-4 text-muted small">{{ $sale->created_at->format('M d, Y — H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x display-1 opacity-25 d-block mb-3"></i>
                                    <strong>No sales data found</strong>
                                    <p class="small mt-1">Try adjusting the filters above.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- pagination part start here ======================================= --}}
            @php
                $perPageOptions = [10, 20, 50, 100];
                $perPageQuery = 'per_page';
                $perPageQueryName = 'per_page';
            @endphp
            <div class="card-footer bg-white py-3 px-4 border-top">
                <div class="row align-items-center m-0">
                    <!-- Left: Showing X to Y -->
                    @include('common.pagination.pagination_data_show', ['data' => $sales])

                    <!-- Center: Items per page -->
                    <div class="col-12 col-md-4 d-flex justify-content-center mb-3 mb-md-0 px-0">
                        <form method="GET" action="{{ $route ?? url()->current() }}"
                            class="d-flex align-items-center m-0">
                            @foreach (request()->except($perPageQueryName ?? 'per_page') as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label class="text-muted small me-2 mb-0 text-nowrap fw-medium">Items per
                                page:</label>
                            <select name="{{ $perPageQueryName ?? 'per_page' }}"
                                class="form-select form-select-sm border-light bg-light rounded-pill fw-medium cursor-pointer"
                                onchange="this.form.submit()" style="width: 80px; min-height: 38px;">
                                @foreach ($perPageOptions ?? [5, 15, 30, 50] as $option)
                                    <option value="{{ $option }}"
                                        {{ request($perPageQueryName ?? 'per_page', 10) == $option ? 'selected' : '' }}>
                                        {{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Right: Pagination Links -->
                    @include('common.pagination.common_pagination', ['data' => $sales])
                </div>
            </div>
            {{-- pagination part end here ======================================= --}}
        </div>

    </div>
@endsection

@push('scripts')
    <!-- Bootstrap Select -->
    <script src="{{ asset('/assets/bootstrap-select-1.14.0-beta3/js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Bootstrap Select
            $('.selectpicker').selectpicker();

            // Initialize Date Range Picker
            $('#date_range').daterangepicker({
                autoUpdateInput: true,
                showDropdowns: true,
                alwaysShowCalendars: true,
                autoApply: false,
                linkedCalendars: false,
                timePicker: false, 

                locale: {
                    format: 'YYYY/MM/DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                }
            });

            // Handle date range input updates
            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
            });

            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endpush
