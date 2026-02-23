@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-shield-lock"></i> Role: <span class="text-primary">{{ ucfirst($role->name) }}</span>
        <small class="text-muted fs-6 ms-2">Page Permissions</small>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.role-permission-association.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Role List
        </a>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm mb-4">
    <i class="bi bi-info-circle-fill me-2"></i>
    <strong>Role-Based Access:</strong> Assigning permissions here will affect all users belonging to the <strong>{{ $role->name }}</strong> role.
</div>

<form action="{{ route('admin.role-permission-association.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="accordion mb-4" id="moduleAccordion">
        @foreach($modules as $module)
            <div class="accordion-item shadow-sm border-0 mb-3">
                <h2 class="accordion-header" id="heading{{ $module->id }}">
                    <div class="accordion-button bg-white text-dark d-flex align-items-center py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}" aria-expanded="true" aria-controls="collapse{{ $module->id }}">
                        <i class="{{ $module->icon }} me-2 text-primary fs-5"></i>
                        <span class="fw-bold me-auto">{{ $module->display_name }} Module</span>
                        
                        <div class="form-check form-switch me-4 mb-0" onclick="event.stopPropagation();">
                            @php
                                $modulePageIds = $module->subModules->flatMap->pages->pluck('id');
                                $moduleCheckedCount = collect($rolePages)->intersect($modulePageIds)->count();
                                $allModulePagesChecked = $modulePageIds->count() > 0 && $moduleCheckedCount === $modulePageIds->count();
                            @endphp
                            <input class="form-check-input select-all-module" type="checkbox" 
                                   id="module_all_{{ $module->id }}" {{ $allModulePagesChecked ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="module_all_{{ $module->id }}">Select Entire Module</label>
                        </div>
                    </div>
                </h2>
                <div id="collapse{{ $module->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $module->id }}">
                    <div class="accordion-body bg-light bg-opacity-10 border-top">
                        <div class="row">
                            @foreach($module->subModules as $subModule)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header border-0 py-2 d-flex justify-content-between align-items-center bg-white">
                                            <span class="fw-bold text-secondary">
                                                <i class="{{ $subModule->icon }} me-1"></i> {{ $subModule->display_name }}
                                            </span>
                                            <div class="form-check form-switch ps-0">
                                                @php
                                                    $allChecked = $subModule->pages->every(function($page) use ($rolePages) {
                                                        return in_array($page->id, $rolePages);
                                                    }) && $subModule->pages->count() > 0;
                                                @endphp
                                                <input class="form-check-input select-all-submodule" type="checkbox" 
                                                       style="margin-left: 0;" {{ $allChecked ? 'checked' : '' }}>
                                                <label class="small text-muted ms-4">All Sub-module</label>
                                            </div>
                                        </div>
                                        <div class="card-body py-3">
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
            </div>
        @endforeach
    </div>

    <div class="mt-4 pb-5 text-center">
        <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
            <i class="bi bi-save me-2"></i> Update Role Permissions
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Select Entire Module" click
        document.querySelectorAll('.select-all-module').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const item = this.closest('.accordion-item');
                item.querySelectorAll('.page-checkbox, .select-all-submodule').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });

        // Handle "Select All Sub-module" toggle click
        document.querySelectorAll('.select-all-submodule').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const card = this.closest('.card');
                card.querySelectorAll('.page-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateModuleLevel(this);
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
                updateModuleLevel(this);
            });
        });

        function updateModuleLevel(el) {
            const item = el.closest('.accordion-item');
            const moduleSelectAll = item.querySelector('.select-all-module');
            const allPageCheckboxes = item.querySelectorAll('.page-checkbox');
            const checkedPageCheckboxes = item.querySelectorAll('.page-checkbox:checked');
            
            moduleSelectAll.checked = allPageCheckboxes.length === checkedPageCheckboxes.length && allPageCheckboxes.length > 0;
        }
    });
</script>
@endpush

<style>
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    .accordion-item {
        border-bottom: 1px solid rgba(0,0,0,.125) !important;
    }
    .card-header {
        background-color: rgba(0,0,0,.03);
    }
</style>
@endsection
