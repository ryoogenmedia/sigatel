<div>
    <x-slot name="title">Guru</x-slot>

    <x-slot name="pageTitle">Guru</x-slot>

    <x-slot name="pagePretitle">Kelola Data Guru</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Guru" :route="route('teacher.create')" />
    </x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <x-modal :show="$show" size="md">
        <form wire:submit.prevent="importExcel" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Import File Excel Guru</h5>
                <button wire:click='closeModal' type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <x-form.input wire:model.lazy="fileExcel" name="fileExcel" label="File Excel" type="file"
                    placeholder="Masukkan file excel"
                    accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" />

                <p>unduh template import excel guru <a href="/template/file-excel-guru.xlsx">disini</a></p>
            </div>

            <div class="modal-footer">
                <div class="btn-list justify-content-end">
                    <button wire:click="resetForm" type="reset" class="btn">Reset</button>

                    <x-datatable.button.save target="importExcel" name="Import Excel" class="btn-green" />
                </div>
            </div>
        </form>
    </x-modal>

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div class="w-100">
                <x-datatable.search placeholder="Cari nama guru..." />
            </div>

            <div class="w-50 ms-2">
                <x-datatable.filter.button target="teacher" />
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

                    <button class="dropdown-item" type="button" wire:click="showModal">
                        <i class="las la-file-excel me-3"></i>

                        <span>Import Excel</span>
                    </button>
                </div>
            </x-datatable.bulk.dropdown>

            <button wire:click="muatUlang" class="btn btn-dark py-1 ms-2"><span
                    class="las la-redo-alt fs-1"></span></button>
        </div>
    </div>

    <x-datatable.filter.card id="teacher">
        <div class="row">
            <div class="col-12 col-lg-4">
                <x-form.select wire:model.live="filters.status" name="filters.status" label="Status">
                    <option value="">- semua -</option>
                    @foreach (config('const.teacher_status') as $status)
                        <option wire:key="{{ $status }}" value="{{ $status }}">{{ ucwords($status) }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="col-12 col-lg-4">
                <x-form.input wire:model.live="filters.nomorPonsel" name="filters.nomorPonsel" label="Nomor Ponsel"
                    type="number" placeholder="Nomor Ponsel" />
            </div>

            <div class="col-12 col-lg-4">
                <x-form.select wire:model.live="filters.jenisKelamin" name="filters.jenisKelamin" label="Jenis Kelamin">
                    <option value="">- semua -</option>
                    @foreach (config('const.sex') as $sex)
                        <option wire:key="{{ $sex }}" value="{{ $sex }}">{{ ucwords($sex) }}</option>
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

                        <th>
                            <x-datatable.column-sort name="Nama" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Nomor Ponsel" wire:click="sortBy('phone_number')"
                                :direction="$sorts['phone_number'] ?? null" />
                        </th>

                        <th style="width: 200px">
                            <x-datatable.column-sort name="Alamat" wire:click="sortBy('address')" :direction="$sorts['address'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Jenis Kelamin" wire:click="sortBy('sex')"
                                :direction="$sorts['sex'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Status" wire:click="sortBy('status')" :direction="$sorts['status'] ?? null" />
                        </th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> guru,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            guru?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> guru.
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
                                        style="background-image: url({{ $row->user->avatarUrl() }})"></span>

                                    <span class="ms-2">{{ $row->name }}</span>
                                </div>
                            </td>

                            <td>{{ $row->phone_number ?? '-' }}</td>

                            <td>{{ $row->address ?? '-' }}</td>

                            <td>{{ ucwords($row->sex ?? '-') }}</td>

                            <td>{{ ucwords($row->status ?? '-') }}</td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm" href="{{ route('teacher.edit', $row->id) }}">
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
