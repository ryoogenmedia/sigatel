<div wire:ignore aria-hidden="true" class="modal fade" id="apiDeletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img alt="" class="img-fluid" src="{{ asset('mobile/assets/images/icons/delete-icon.png') }}">
                <div class="text-center">
                    <h4 class="text-danger f-w-600">Apakah Anda Yakin</h4>
                    <p class="text-secondary f-s-16">Apa yang anda lakukan tidak bisa dikembalikan.</p>
                </div>

                <div class="text-center mt-3">
                    <button wire:click="cancelDelete" class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">Batal
                    </button>
                    <button wire:click="deleteData" class="btn btn-light-primary" id="confirmDelete"
                        type="button">Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('mobile/assets/js/filemanager.js') }}"></script>
@endpush
