<div class="container-fluid">
    <x-mobile.title-page title="Tugas Piket" subtitle="Tugas Piket Anda." />

    <x-mobile.modal-delete />

    <x-mobile.alert />

    <div class="card">
        <div class="p-2">
            <div class="alert alert-border-info mt-3 rounded-2" role="alert">
                <h6>
                    <i class="ti ti-info-circle f-s-18 me-2 text-info"></i>
                    Anda Bertugas Sebagai Piket.
                </h6>
                <p>Sekarang anda berperan sebagai guru piket, anda dapat menambahkan pelanggaran kepada siswa.
                </p>
            </div>
        </div>

        <div class="card-header mb-2">
            <div class="d-flex gap-2 justify-content-between flex-sm-row flex-column">
                <h5>Daftar Siswa Melanggar</h5>
                <a class="btn btn-primary-dark b-r-22" type="button" href="{{ route('mobile.duty.create') }}">Tambah
                    Siswa Melanggar
                </a>
            </div>

            <div class="my-3">
                <x-form.input wire:model.live="filters.search" name="filters.search" type="text"
                    placeholder="Cari nama siswa / nama guru..." autofocus />
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bottom-border  recent-table align-middle table-hover mb-0"
                    id="recentdatatable">
                    <thead>
                        <tr>
                            <th scope="col">Nama Siswa</th>
                            <th scope="col">Guru Piket</th>
                            <th scope="col">Jenis Pelanggaran</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="recent_key_body">
                        @forelse ($this->rows as $row)
                            <tr>
                                <td>
                                    <div>
                                        <img alt="" class="w-20 h-20"
                                            src="{{ $row->student->user->avatarUrl() }}">
                                        <span class="ms-2 table-text">{{ $row->student->name ?? '-' }}</span>
                                    </div>
                                </td>

                                <td>{{ $row->teacher->name ?? '-' }}</td>

                                <td>{{ $row->violation_type ?? '-' }}</td>

                                <td>{!! $row->description ?? '-' !!}</td>

                                <td class="d-flex">
                                    <div class="dropdown folder-dropdown">
                                        <a aria-expanded="true" class="" data-bs-toggle="dropdown" role="button">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item view-item-btn"
                                                    href="{{ route('mobile.duty.edit', $row->id) }}"><i
                                                        class="ti ti-edit text-primary me-2"></i>
                                                    Sunting</a>
                                            </li>
                                            <li>
                                                <button wire:click="getDataId({{ $row->id }})"
                                                    class="dropdown-item delete-btn" data-bs-toggle="modal"
                                                    role="button"><i class="ti ti-trash text-danger me-2"></i>
                                                    Hapus</button>
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
