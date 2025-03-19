<div>
    <x-slot name="title">Penugasan Piket Siswa</x-slot>

    <div class="row g-2 align-items-center mb-4">
        <div class="col">
            <div class="page-pretitle">
                Cetak Laporan Piket Siswa
            </div>
            <h2 class="page-title">
                Laporan Piket Siswa
            </h2>
        </div>

        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <button class="btn btn-danger"><span class="las la-print fs-1 me-2"></span>Cetak Piket Siswa</button>
            </div>
        </div>
    </div>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div class="w-100">
                <x-datatable.search placeholder="Cari Nama Orang Tua Siswa..." />
            </div>

            <div class="w-50 ms-2">
                <x-datatable.filter.button target="report-piket" />
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-3">
            <x-datatable.items-per-page />
        </div>
    </div>

    <x-datatable.filter.card id="report-piket">
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
                        <th style="width: 200px">Siswa</th>

                        <th>Status</th>

                        <th>Deskripsi</th>

                        <th>Jenis Pelanggaran</th>

                        <th>Jadwal Penugasan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>{{ $row->student->name ?? '-' }}</td>

                            <td>{{ ucwords($row->status ?? '-') }}</td>

                            <td>{{ $row->description ?? '-' }}</td>

                            <td>{{ ucwords($row->violation_type ?? '-') }}</td>

                            <td>
                                <span class="me-2 mb-2">{{ $row->jadwal_penugasan }}</span>
                                <span>{{ $row->jadwal_selesai }}</span>
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
