<div class="d-print-none">
    <div style="z-index: 9999; position: fixed; bottom: 20px; right: 20px;"
        class="d-flex flex-column align-items-end gap-2">

        <span class="btn btn-warning" id="loading-indicator" wire:loading.delay>
            <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span>
            Memuat Data Baru...<span class="animated-dots"></span>
        </span>

        <span class="btn btn-red" id="loading-indicator" wire:offline>
            <i class="las la-plane me-2"></i> Anda sedang offline.
        </span>
    </div>



    @if (session('alert'))
        <div class="alert alert-{{ $type }} alert-dismissible bg-white" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <h1 class="text-{{ $type }} las la-{{ $icon }}"></h1>
                </div>

                <div>
                    <h4 class="alert-title">{{ $message }}</h4>
                    <div class="text-muted">{{ $detail }}</div>
                </div>
            </div>

            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif
</div>
