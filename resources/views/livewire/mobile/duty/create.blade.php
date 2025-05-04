<div class="container-fluid">
    <x-mobile.title-page title="Tambah Pelanggaran Siswa" subtitle="Menambah Pelanggaran Siswa." />

    <x-mobile.alert />

    <div class="row">
        <form class="col-12" wire:submit="save">
            <div class="card">
                <div class="card-body px-3">
                    <div class="alert alert-border-info mt-3" role="alert">
                        <h6>
                            <i class="ti ti-info-circle f-s-18 me-2 text-info"></i>
                            Pilih Siswa Terlebih Dahulu
                        </h6>
                        <p>
                            Pastikan anda memilih siswa yang melanggar terlebih dahulu, anda dapat mencari berdasarkan
                            nama, nim atau kelas.
                        </p>
                    </div>

                    @unless ($this->studentId)
                        <div class="card border">
                            <div class="card-header">
                                <div class="d-flex gap-2 justify-content-between flex-sm-row flex-column">
                                    <h5>Pilih Siswa</h5>
                                    <a href="{{ route('mobile.duty.index') }}" class="btn btn-light-primary b-r-22">Kembali
                                        Ke
                                        Halaman Utama</a>
                                </div>

                                <div class="my-3">
                                    <x-form.input wire:model.live="filters.search" name="filters.search"
                                        placeholder="cari kelas / nama / nis..." type="text" />
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive app-scroll app-datatable-default my-3">
                                    <table class="w-100 display ticket-app-table" id="ticketdatatable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center px-2">Siswa</th>
                                                <th class="text-center px-2">NIS</th>
                                                <th class="text-center px-2">Kelas</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ticket_key_body">
                                            @forelse ($this->students as $student)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="ps-3">
                                                        {{ $student->name ?? '-' }}
                                                    </td>
                                                    <td class="ps-3">
                                                        {{ $student->nis ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $student->grade->name ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button wire:click="chooseStudent({{ $student->id }})"
                                                            type="button"
                                                            class="btn btn-sm btn-primary-dark">Pilih</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-mobile.empty-data />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ $this->students->links() }}
                            </div>
                        </div>
                    @endunless

                    @if ($this->studentId)
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <p>Anda Telah Memilih Siswa</p>
                                        <p class="mb-0 pb-1" style="font-weight: bold; font-size: 13px">
                                            Nama : {{ $this->studentName }}</p>
                                        <p style="font-weight: 600; font-size: 12px">NIS : {{ $this->studentNIS }}</p>
                                        <button wire:click="cancelStudent" type="button"
                                            class="btn btn-sm bg-danger mt-1">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card border">
                        <div class="card-header">
                            <h4>Input Data Pelanggaran</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <x-form.textarea wire:model="keteranganPelanggaran" name="keteranganPelanggaran"
                                        label="Keterangan Pelanggaran" />

                                    @foreach ($this->violation_types as $type)
                                        <x-form.check wire:key="row-{{ $type->id }}" wire:model="jenisPelanggaran"
                                            type="radio" description="{{ ucwords($type->name) }}"
                                            name="jenisPelanggaran" value="{{ strtolower($type->name) }}" />
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="col-12">
                                <button wire:click="save" type="submit" class="btn btn-primary-dark w-100">Berikan
                                    Tugas</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
