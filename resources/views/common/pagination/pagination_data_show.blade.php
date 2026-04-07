<div class="col-12 col-md-4 text-center text-md-start mb-3 mb-md-0 px-0">
    <div class="text-muted small fw-medium">
        Showing <span class="fw-bold text-dark">{{ $data->firstItem() ?? 0 }}</span> to <span
            class="fw-bold text-dark">{{ $data->lastItem() ?? 0 }}</span> of <span
            class="fw-bold text-dark">{{ $data->total() }}</span> {{ $item_name ?? 'entries' }}
    </div>
</div>
