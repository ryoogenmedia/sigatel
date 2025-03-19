<div>
    <x-slot name="title">Kelas</x-slot>

    <div class="row g-2 align-items-center mb-4">
        <div class="col">
            <div class="page-pretitle">
                Cetak Laporan Kelas
            </div>
            <h2 class="page-title">
                Laporan Kelas
            </h2>
        </div>

        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <button class="btn btn-danger"><span class="las la-print fs-1 me-2"></span>Cetak Kelas</button>
            </div>
        </div>
    </div>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div class="w-100">
                <x-datatable.search placeholder="Cari nama wali kelas..." />
            </div>

            <div class="w-50 ms-2">
                <x-datatable.filter.button target="report-kelas" />
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-3">
            <x-datatable.items-per-page />
        </div>
    </div>

    <x-datatable.filter.card id="report-kelas">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.date_start" name="filters.date_start" type="month"
                    label="Tahun & Bulan Awal" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.date_end" name="filters.date_end" type="month"
                    label="Tahun & Bulan Akhir" />
            </div>
        </div>
    </x-datatable.filter.card>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>

                        <th>Lantai Gedung</th>

                        <th>Jumlah Murid</th>

                        <th>Wali Kelas</th>

                        <th>Status Wali Kelas</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td class="text-center"><b>{{ $row->name ?? '-' }}</b></td>

                            <td class="text-center">{{ $row->floor ?? '-' }}</td>

                            <td class="text-center">{{ $row->total_student ?? 0 }}</td>

                            <td>{{ $row->teacher->name ?? '-' }}</td>

                            <td>{{ $row->teacher->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}</td>
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
