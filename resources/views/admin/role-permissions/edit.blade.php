@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Associate Page Permissions: {{ ucfirst($role->name) }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.role-permissions.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    @foreach($modules as $module)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="{{ $module->icon }}"></i> {{ $module->display_name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($module->subModules as $subModule)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-light shadow-sm">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
                                    <span class="fw-bold"><i class="{{ $subModule->icon }} me-1"></i> {{ $subModule->display_name }}</span>
                                    <div class="form-check form-switch ps-0">
                                        @php
                                            $allChecked = $subModule->pages->every(function($page) use ($rolePages) {
                                                return in_array($page->id, $rolePages);
                                            }) && $subModule->pages->count() > 0;
                                        @endphp
                                        <input class="form-check-input select-all-submodule" type="checkbox" style="margin-left: 0;" {{ $allChecked ? 'checked' : '' }}>
                                        <label class="small text-muted ms-4">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row">
                                        @foreach($subModule->pages as $page)
                                            <div class="col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input page-checkbox" type="checkbox" name="pages[]" 
                                                           value="{{ $page->id }}" id="page_{{ $page->id }}"
                                                           {{ in_array($page->id, $rolePages) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="page_{{ $page->id }}">
                                                        {{ $page->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-4 pb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
            <i class="bi bi-save"></i> Save All Permissions
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Select All" toggle click
        document.querySelectorAll('.select-all-submodule').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const card = this.closest('.card');
                card.querySelectorAll('.page-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });

        // Handle individual checkbox change
        document.querySelectorAll('.page-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const card = this.closest('.card');
                const selectAll = card.querySelector('.select-all-submodule');
                const allCheckboxes = card.querySelectorAll('.page-checkbox');
                const checkedCheckboxes = card.querySelectorAll('.page-checkbox:checked');
                
                selectAll.checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
            });
        });
    });
</script>
@endpush
@endsection
