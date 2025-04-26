<div>
    <x-slot name="title">Pelanggaran Siswa</x-slot>

    <x-slot name="pageTitle">Pelanggaran Siswa</x-slot>

    <x-slot name="pagePretitle">Kelola Data Pelanggaran Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Pelanggaran Siswa" :route="route('on-duty.student-violation.create')" />
    </x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <div>
                <x-datatable.search placeholder="Cari nama siswa / guru piket..." />
            </div>

            <div class="ms-2">
                <x-form.select wire:model.lazy="filters.type" name="filters.type" form-group-class>
                    <option value="">- semua jenis pelanggaran -</option>
                    @foreach ($this->getViolationType as $type)
                        <option wire:key='{{ $type->violation_type }}' value="{{ $type->violation_type }}">
                            {{ ucwords($type->violation_type) }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="violation" />
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

    <x-datatable.filter.card id="violation">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.date_start" name="filters.date_start" type="date"
                    label="Tanggal Awal" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.date_end" name="filters.date_end" type="date"
                    label="Tanggal Akhir" />
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

                        <th>Siswa</th>

                        <th>Guru Piket</th>

                        <th>
                            <x-datatable.column-sort name="Pelanggaran" wire:click="sortBy('violation_type')"
                                :direction="$sorts['violation_type'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Keterangan" wire:click="sortBy('description')"
                                :direction="$sorts['description'] ?? null" />
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
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> pelanggaran
                                            siswa,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            pelanggaran siswa?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> pelanggaran siswa.
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

                            <td>{{ $row->student->name ?? '-' }}</td>

                            <td>{{ $row->teacher->name ?? '-' }}</td>

                            <td>{{ ucwords($row->violation_type) ?? '-' }}</td>

                            <td>{{ $row->description ?? '-' }}</td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm"
                                            href="{{ route('on-duty.student-violation.edit', $row->id) }}">
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
