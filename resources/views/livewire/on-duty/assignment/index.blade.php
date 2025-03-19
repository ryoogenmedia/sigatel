<div>
    <x-slot name="title">Penugasan Piket Siswa</x-slot>

    <x-slot name="pageTitle">Penugasan Piket Siswa</x-slot>

    <x-slot name="pagePretitle">Kelola Data Penugasan Piket Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Penugasan Piket Siswa" :route="route('on-duty.assignment.create')" />
    </x-slot>

    <x-alert />

    @if (!session('alert'))
        <div class="alert alert-info alert-dismissible bg-white" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <h1 class="text-info las la-info-circle"></h1>
                </div>

                <div>
                    <h4 class="alert-title">Pastikan Data Dokumentasi Ada.</h4>
                    <div class="text-muted">Isi data dokumentasi seperti foto siswa, foto kegiatan piket serta lokasi
                        sebagai bukti yang dapat di verifikasi.
                    </div>
                </div>
            </div>

            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif


    <x-modal.delete-confirmation />

    <x-modal :show="$this->modalImageDoc" size="lg">
        <form wire:submit.prevent="saveUploadImage" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Upload File Gambar</h5>
                <button wire:click='closeModal' type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        @if ($this->dokumentasiSiswa)
                            <img class="rounded-4 object-fit-cover" style="width: 100%; height: 200px"
                                src="{{ $this->dokumentasiSiswa->temporaryUrl() }}" alt="logo">
                        @else
                            <img class="rounded-4 object-fit-contain"
                                style="width: 100%; height: 200px; background-color: #eeeeee"
                                src="{{ $this->photoStudentUrl }}" alt="logo-default">
                        @endif

                        <div class="mt-3">
                            <x-form.input wire:model="dokumentasiSiswa" name="dokumentasiSiswa"
                                label="Dokumentasi Siswa" type="file"
                                optional="Abaikan jika tidak ingin mengubah." />
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        @if ($this->dokumentasiKegiatan)
                            <img class="rounded-4 object-fit-cover" style="width: 100%; height: 200px"
                                src="{{ $this->dokumentasiKegiatan->temporaryUrl() }}" alt="logo">
                        @else
                            <img class="rounded-4 object-fit-contain"
                                style="width: 100%; height: 200px; background-color: #eeeeee"
                                src="{{ $this->dokumentasiKegiatanUrl }}" alt="logo-default">
                        @endif

                        <div class="mt-3">
                            <x-form.input wire:model="dokumentasiKegiatan" name="dokumentasiKegiatan"
                                label="Dokumentasi Kegiatan" type="file"
                                optional="Abaikan jika tidak ingin mengubah." />
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-list justify-content-end">
                    <button wire:click="resetForm" type="reset" class="btn">Reset</button>

                    <x-datatable.button.save target="saveUploadImage" name="Simpan Perubahan" class="btn-green" />
                </div>
            </div>
        </form>
    </x-modal>

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <div>
                <x-datatable.search placeholder="Cari nama siswa..." />
            </div>

            <div class="ms-2">
                <x-form.select wire:model.lazy="filters.status" name="filters.status" form-group-class>
                    <option value="">- semua status -</option>
                    @foreach (config('const.duty_status') as $status)
                        <option wire:key='{{ $status }}' value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="assignment-duty" />
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-3">
            <x-datatable.items-per-page />

            <x-datatable.bulk.dropdown>
                <div class="dropdown-menu dropdown-menu-end datatable-dropdown">
                    <button data-bs-toggle="modal" data-bs-target="#delete-confirmation" class="dropdown-item"
                        type="button">
                        <i class="las la-trash me-3"></i>

                        <span>Hapus</span>
                    </button>
                </div>
            </x-datatable.bulk.dropdown>
        </div>
    </div>

    <x-datatable.filter.card id="assignment-duty">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.scheduleTime" name="filters.scheduleTime" type="datetime-local"
                    label="Tanggal & Waktu Penugasan" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.finishTime" name="filters.finishTime" type="datetime-local"
                    label="Tanggal & Waktu Selesai" />
            </div>

            <div class="col-12">
                <x-form.select wire:model.live="filters.category" name="filters.category" label="Kategori Penugasan">
                    <option value="">- semua kategori -</option>
                    @foreach (config('const.violation_type') as $category)
                        <option wire:key="{{ $category }}" value="{{ $category }}">{{ ucwords($category) }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>
        </div>
    </x-datatable.filter.card>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>

                        <th style="width: 200px">Siswa</th>

                        <th>
                            <x-datatable.column-sort name="Status" wire:click="sortBy('status')" :direction="$sorts['status'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Deskripsi" wire:click="sortBy('description')"
                                :direction="$sorts['description'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Jenis Pelanggaran" wire:click="sortBy('violation_type')"
                                :direction="$sorts['violation_type'] ?? null" />
                        </th>

                        <th style="width: 10px">Jadwal Penugasan</th>

                        <th>Dokumentasi</th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> piket,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            piket?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> piket.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm px-3"
                                        style="background-image: url({{ $row->student->user->avatarUrl() }})"></span>

                                    <span class="ms-2">{{ $row->student->name }}</span>
                                </div>
                            </td>

                            <td>
                                <span @class([
                                    'badge',
                                    'bg-lime' => $row->status == 'approved',
                                    'bg-orange' => $row->status == 'pending',
                                    'bg-red' => $row->status == 'reject',
                                ])>{{ $row->status }}</span>
                            </td>

                            <td>{{ $row->description ?? '-' }}</td>

                            <td>{{ ucwords($row->violation_type ?? '-') }}</td>

                            <td>
                                <span class="badge bg-green-lt me-2 mb-2">{{ $row->jadwal_penugasan }}</span>
                                <span class="badge bg-red-lt">{{ $row->jadwal_selesai }}</span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button wire:click="showImageDoc({{ $row->id }})"
                                        class="btn btn-sm btn-dark">
                                        <i class="las la-camera fs-1"></i>
                                    </button>

                                    <button class="btn btn-sm btn-red">
                                        <i class="las la-map-marker fs-1"></i>
                                    </button>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm"
                                            href="{{ route('on-duty.assignment.edit', $row->id) }}">
                                            Sunting
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-datatable.empty colspan="10" />
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $this->rows->links() }}
    </div>
</div>
