<div>
    <x-slot name="title">Jenis Pelanggaran</x-slot>

    <x-slot name="pageTitle">Jenis Pelanggaran</x-slot>

    <x-slot name="pagePretitle">Kelola Data Jenis Pelanggaran</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row">
        <div class="col-12 col-lg-4">
            <form wire:submit="saveViolationType" class="card">
                <div class="card-header">Form Jenis Pelanggaran</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-form.input wire:model.lazy="name" name="name" label="Nama Pelanggaran"
                                placeholder="Masukkan nama pelanggaran" type="text" />

                            <x-form.toggle wire:model.lazy="status" name="status" label="Status" />

                            <x-form.textarea wire:model.lazy="description" name="description" label="Deskripsi"
                                placeholder="Masukkan deskripsi pelanggaran" />
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="btn-list justify-content-end">
                        <button type="reset" class="btn">Reset</button>

                        <x-datatable.button.save target="saveViolationType"
                            name="{{ $this->violationTypeId ? 'Sunting' : 'Tambah' }}" class="btn btn-success" />
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-lg-8">
            <div class="row mb-3 align-items-center justify-content-between">
                <div class="col-12 col-lg-6 d-flex align-self-center">
                    <div>
                        <x-datatable.search placeholder="Cari nama jenis pelanggaran..." />
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

                    <button wire:click="muatUlang" class="btn btn-dark py-1 ms-2"><span
                            class="las la-redo-alt fs-1"></span></button>
                </div>
            </div>

            <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
                <div class="table-responsive mb-0">
                    <table class="table card-table table-bordered datatable">
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <x-datatable.bulk.check wire:model.lazy="selectPage" />
                                </th>

                                <th>
                                    <x-datatable.column-sort name="Nama Pelanggaran" wire:click="sortBy('name')"
                                        :direction="$sorts['name'] ?? null" />
                                </th>

                                <th>
                                    <x-datatable.column-sort name="Deksripsi" wire:click="sortBy('description')"
                                        :direction="$sorts['description'] ?? null" />
                                </th>

                                <th>
                                    <x-datatable.column-sort name="Status" wire:click="sortBy('status')"
                                        :direction="$sorts['status'] ?? null" />
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
                                                <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong>
                                                    jenis
                                                    pelanggaran,
                                                    apakah
                                                    Anda mau memilih semua
                                                    <strong>{{ $this->rows->total() }}</strong>
                                                    jenis pelanggaran?</span>

                                                <button wire:click="selectedAll" class="btn ms-2">Pilih
                                                    Semua</button>
                                            </div>
                                        @else
                                            <span class="text-pink">Anda sekarang memilih semua
                                                <strong>{{ count($this->selected) }}</strong> jenis pelanggaran.
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            @forelse ($this->rows as $row)
                                <tr wire:key="row-{{ $row->id }}"
                                    class="{{ $row->id == $this->violationTypeId ? 'bg-green-lt' : '' }}">
                                    <td>
                                        <x-datatable.bulk.check wire:model.lazy="selected"
                                            value="{{ $row->id }}" />
                                    </td>

                                    <td>{{ $row->name }}</td>

                                    <td>{{ $row->description }}</td>

                                    <td>
                                        <x-form.toggle wire:change='changeStatus({{ $row->id }})'
                                            name="changeStatus" :checked="$row->status == 1 ? true : false" />
                                    </td>

                                    <td>
                                        @if ($this->violationTypeId == $row->id)
                                            <button wire:click="closeModal" class="btn btn-danger" type="button">Batal
                                                <span class="las la-times fs-2 ms-1"></span></button>
                                        @else
                                            <button wire:click="openModal({{ $row->id }})" class="btn btn-dark"
                                                type="button">Sunting <span
                                                    class="las la-pencil-alt fs-1 ms-1"></span></button>
                                        @endif
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
    </div>
