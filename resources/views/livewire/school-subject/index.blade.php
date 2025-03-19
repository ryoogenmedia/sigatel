<div>
    <x-slot name="title">Mata Pelajaran</x-slot>

    <x-slot name="pageTitle">Mata Pelajaran</x-slot>

    <x-slot name="pagePretitle">Kelola Data Mata Pelajaran</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Mata Pelajaran" :route="route('school-subject.create')" />
    </x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <x-datatable.search placeholder="Cari Nama Mata Pelajaran..." />
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
                            <x-datatable.column-sort name="Kode" wire:click="sortBy('code')" :direction="$sorts['code'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Mata Pelajaran" wire:click="sortBy('name')"
                                :direction="$sorts['name'] ?? null" />
                        </th>

                        <th style="width: 10px">Status</th>

                        <th>Pendidik</th>

                        <th>Status Pendidik</th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> mata
                                            pelajaran,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            mata pelajaran?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> mata pelajaran.
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

                            <td><b>{{ $row->code ?? '-' }}</b></td>

                            <td>{{ $row->name }}</td>

                            <td>
                                <x-form.toggle wire:change="changeStatusMapel({{ $row->id }})"
                                    name="changeStatusMapel" :checked="$row->status == 1 ? true : false" />
                            </td>

                            <td>{{ $row->teacher->name ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $row->teacher->status == 'aktif' ? 'green' : 'red' }}-lt">{{ $row->teacher->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}</span>
                            </td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm" href="{{ route('school-subject.edit', $row->id) }}">
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
