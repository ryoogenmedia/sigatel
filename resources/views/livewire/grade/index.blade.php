<div>
    <x-slot name="title">Kelas</x-slot>

    <x-slot name="pageTitle">Kelas</x-slot>

    <x-slot name="pagePretitle">Kelola Data Kelas</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Kelas" :route="route('user.create')" />
    </x-slot>

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

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>

                        <th style="width: 10px">Nama Kelas</th>

                        <th style="width:10px">Lantai Gedung</th>

                        <th style="width:10px">Jumlah Murid</th>

                        <th>Wali Kelas</th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> kelas,
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
                            <td>
                                <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                            </td>

                            <td class="text-center"><b>{{ $row->name ?? '-' }}</b></td>

                            <td class="text-center">{{ $row->floor ?? '-' }}</td>

                            <td class="text-center">{{ $row->total_student ?? 0 }}</td>

                            <td>{{ $row->teacher->name ?? '-' }}
                                <span class="ms-2 badge bg-{{ $row->teacher->status == 'aktif' ? 'lime' : 'red' }}-lt">
                                    {{ $row->teacher->status }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm" href="{{ route('user.edit', $row->id) }}">
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
