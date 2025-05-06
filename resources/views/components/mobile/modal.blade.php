<div wire:ignore aria-hidden="true" aria-labelledby="{{ $name }}Label" class="modal fade " id="{{ $name }}"
    tabindex="-1">
    <div class="modal-dialog modal-fullscreen-{{ $size }}-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="{{ $name }}Label">{{ $title }}</h6>
                <button wire:click="cancelData" aria-label="Close" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                    type="button"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button wire:click="cancelData" class="btn btn-light-secondary btn-sm" data-bs-dismiss="modal"
                    type="button">Tutup</button>
            </div>
        </div>
    </div>
</div>
