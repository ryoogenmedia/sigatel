@if (session('alert'))
    <div class="alert alert-light-border-{{ $type }} d-flex align-items-center justify-content-between"
        role="alert">
        <p class="mb-0">
            <i class="ti ti-{{ $icon }} f-s-18 me-2 ms-2"></i>{{ $message }} {{ $detail }}
        </p>
        <i class="ti ti-x" data-bs-dismiss="alert"></i>
    </div>
@endif
