<div wire:ignore aria-hidden="true" class="modal fade" id="apiDeletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img alt="" class="img-fluid" src="{{ asset('ryoogen/illustration/confirm.jpg') }}">
                <div class="text-center mt-3 mb-4">
                    <h4 class="text-primary-dark f-w-600">Apakah Anda Yakin Telah Memberikan Tugas</h4>
                    <p class="text-secondary f-s-16">Apa yang anda lakukan harus anda pertanggung jawabkan.</p>
                </div>

                <div class="text-center mt-3">
                    <button wire:click="cancelData" class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">Batal
                    </button>
                    <button wire:click="changeStatus" class="btn btn-light-primary" id="confirmDelete"
                        type="button">Setujui Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('mobile/assets/js/filemanager.js') }}"></script>
@endpush
