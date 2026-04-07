<div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end px-0">
    <div class="pagination-wrapper">
        @if ($data->hasPages())
            {{ $data->withQueryString()->links() }}
        @else
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&lsaquo; Previous</span>
                    </li>
                    <li class="page-item active" aria-current="page"><span class="page-link">1</span>
                    </li>
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next &rsaquo;</span>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
