<div>
    <x-slot name="title">Kelas</x-slot>

    <x-slot name="pageTitle">Kelas</x-slot>

    <x-slot name="pagePretitle">Kelola Data Kelas</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <x-datatable.search placeholder="Cari nama kelas / wali kelas..." />
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

    <div class="row">
        <div class="col-12 col-lg-4">
            <form class="card" wire:submit.prevent="save" autocomplete="off">
                <div class="card-header">
                    {{ $this->gradeId ? 'Sunting' : 'Tambah' }} Data Kelas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-form.input wire:model.lazy="namaKelas" name="namaKelas" label="Nama Kelas" type="text"
                                placeholder="Nama Kelas" required autofocus />

                            <x-form.input wire:model.lazy="nomorLantai" name="nomorLantai" label="Nomor Lanti"
                                type="number" placeholder="Nomor Lantai" required />

                            <x-form.select wire:model.lazy="waliKelas" name="waliKelas" label="Wali Kelas" required>
                                <option value="">- pilih wali kelas -</option>
                                @foreach ($this->teachers as $teacher)
                                    <option wire:key="{{ $teacher->id }}" value="{{ $teacher->id }}">
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </x-form.select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-list justify-content-end">
                        <button wire:click="resetData" type="reset" class="btn">Reset</button>

                        <x-datatable.button.save class="btn btn-{{ $this->gradeId ? 'warning' : 'success' }}"
                            target="save" name="{{ $this->gradeId ? 'Sunting' : 'Tambah' }} Kelas" />
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
                <div class="table-responsive mb-0">
                    <table class="table card-table table-bordered datatable">
                        <thead>
                            <tr>
                                @unless ($this->gradeId)
                                    <th class="w-1">
                                        <x-datatable.bulk.check wire:model.lazy="selectPage" />
                                    </th>
                                @endunless

                                <th style="width: 10px">Nama Kelas</th>

                                <th style="width:10px">Lantai Gedung</th>

                                <th style="width:10px">Jumlah Murid</th>

                                <th>Wali Kelas</th>

                                @unless ($this->gradeId)
                                    <th style="width: 10px"></th>
                                @endunless
                            </tr>
                        </thead>

                        <tbody>
                            @if ($selectPage)
                                <tr>
                                    <td colspan="10" class="bg-green-lt">
                                        @if (!$selectAll)
                                            <div class="text-green">
                                                <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong>
                                                    kelas,
                                                    apakah
                                                    Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                                    kelas?</span>

                                                <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                            </div>
                                        @else
                                            <span class="text-pink">Anda sekarang memilih semua
                                                <strong>{{ count($this->selected) }}</strong> kelas.
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            @forelse ($this->rows as $row)
                                <tr wire:key="row-{{ $row->id }}">
                                    @unless ($this->gradeId)
                                        <td>
                                            <x-datatable.bulk.check wire:model.lazy="selected"
                                                value="{{ $row->id }}" />
                                        </td>
                                    @endunless

                                    <td class="text-center"><b>{{ $row->name ?? '-' }}</b></td>

                                    <td class="text-center">{{ $row->floor ?? '-' }}</td>

                                    <td class="text-center">{{ $row->total_student ?? 0 }}</td>

                                    <td><span class="me-1">{{ $row->teacher->name ?? '-' }}</span>
                                        <span
                                            class="badge bg-{{ $row->teacher->status == 'aktif' ? 'lime' : 'red' }}-lt">
                                            {{ $row->teacher->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>

                                    @unless ($this->gradeId)
                                        <td>
                                            <div class="d-flex">
                                                <div class="ms-auto">
                                                    <button class="btn btn-sm" wire:click="getGrade({{ $row->id }})">
                                                        Sunting
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endunless
                                </tr>
                            @empty
                                <x-datatable.empty colspan="10" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($this->gradeId)
                    <div class="p-3">
                        <button wire:click="resetData" class="btn w-100">Batal Edit Data</button>
                    </div>
                @endif

                {{ $this->rows->links() }}
            </div>
        </div>
    </div>
</div>
