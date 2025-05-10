<div class="container-fluid">
    <x-mobile.title-page title="Pemberian Tugas Kelas" subtitle="Berikan Tugas Kepada Kelas Siswa." />

    {{-- <x-mobile.modal-delete /> --}}

    <x-mobile.alert />

    <x-mobile.modal-confirm />

    <x-mobile.modal name="modalDetail" size="sm" title="Detail Informasi">
        <div class="my-3">
            <h5 class="mb-2">Alasan Guru</h5>
            <p id="alasanGuru"></p>
        </div>

        <div class="my-3">
            <h5 class="mb-2">Penjelasan Tugas</h5>
            <div id="penjelasanTugas"></div>
        </div>

        <div class="my-3">
            <h5 class="mb-2">Tanggal Pemberian - Tanggal Kumpul</h5>
            <div>
                <span id="tanggalPemberian"></span> -
                <span id="tanggalKumpul"></span>
            </div>
        </div>

        <div class="my-3">
            <form wire:submit="addDokumentasi" class="row">
                <div class="col-12 d-flex">
                    <div class="mx-auto mt-3 mb-4">
                        @if ($dokumentasi)
                            <img src="{{ $dokumentasi->temporaryUrl() }}" alt="Dokumentasi" class="img-fluid rounded">
                        @else
                            <img id="gambarDokumentasi" src="{{ asset('ryoogenmedia/no-image.png') }}"
                                alt="No Image Available" class="img-fluid rounded">
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <x-form.input wire:model.lazy="dokumentasi" name="dokumentasi" type="file" label="Dokumentasi" />
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Tambahkan Dokumentasi</button>
                </div>
            </form>
        </div>
    </x-mobile.modal>

    <div class="card">
        <div class="p-2">
            <div class="alert alert-border-info mt-3 rounded-2" role="alert">
                <h6>
                    <i class="ti ti-info-circle f-s-18 me-2 text-info"></i>
                    Anda Bertugas Sebagai Piket.
                </h6>
                <p>Sekarang anda wajib, memberikan tugas kepada kelas.
                </p>
            </div>
        </div>

        <div class="card-header mb-2">
            <div class="d-flex gap-2 justify-content-between flex-sm-row flex-column">
                <h5>Daftar Tugas Kelas</h5>
            </div>

            <div class="my-3">
                <x-form.input wire:model.live="filters.search" name="filters.search" type="text"
                    placeholder="Cari nama kelas / nama guru pemberi tugas..." autofocus />
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bottom-border  recent-table align-middle table-hover mb-0"
                    id="recentdatatable">
                    <thead>
                        <tr>
                            <th>Nama Guru Pemberi Tugas</th>
                            <th>Kelas</th>
                            <th>Status Pemberian</th>
                            <th>File Tugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="recent_key_body">
                        @forelse ($this->rows as $row)
                            <tr>
                                <td>
                                    <span class="table-text">{{ $row->teacher->name ?? '-' }}</span>
                                </td>

                                <td><b>{{ $row->grade->name ?? '-' }}</b></td>

                                <td><small
                                        class="text-{{ $row->status ? 'success' : 'danger' }}">{{ $row->status ? 'sudah' : 'belum' }}
                                        @if ($row->status)
                                            <i class="iconoir-check"></i>
                                        @else
                                            <i class="iconoir-xmark"></i>
                                        @endif
                                    </small>
                                </td>

                                <td>
                                    @if ($row->fileAssignment())
                                        <a class="text-white btn btn-primary-dark btn-sm"
                                            href="https://docs.google.com/gview?url={{ $row->fileAssignment() }}&embedded=true"
                                            target="_blank"
                                            onclick="openPdfInNewWindow('{{ $row->fileAssignment() }}'); return false;">file</a>
                                    @else
                                        <small>tanpa file</small>
                                    @endif
                                </td>

                                <td class="d-flex">
                                    <div class="dropdown folder-dropdown">
                                        <a aria-expanded="true" class="" data-bs-toggle="dropdown" role="button">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button wire:click="getDataId({{ $row->id }})"
                                                    class="dropdown-item" data-bs-target="#modalDetail"
                                                    data-bs-toggle="modal" type="button"><i
                                                        class="ti ti-eye text-primary-dark me-2"></i>
                                                    Detail Tugas</button>

                                                <button wire:click="getDataId({{ $row->id }})"
                                                    class="dropdown-item delete-btn" data-bs-toggle="modal"
                                                    role="button"><i class="ti ti-checklist text-success me-2"></i>
                                                    Tandai Selesai</button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-mobile.empty-data />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function openPdfInNewWindow(url) {
            let width = screen.width * 0.8;
            let height = screen.height * 0.8;
            let left = (screen.width - width) / 2;
            let top = (screen.height - height) / 2;

            window.open(url, '_blank',
                `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
        }
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('pushData', (data) => {
                document.getElementById('alasanGuru').innerText = data[0].alasanGuru ?? '';
                document.getElementById('tanggalPemberian').innerText = data[0].tanggalPemberian ?? '';
                document.getElementById('tanggalKumpul').innerText = data[0].tanggalKumpul ?? '';
                document.getElementById('penjelasanTugas').innerHTML = data[0].penjelasanTugas ?? '';
                document.getElementById('gambarDokumentasi').src = data[0].dokumentasi ?? '';
            });
        });
    </script>
@endpush
