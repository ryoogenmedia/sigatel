<div>
    <x-slot name="title">Tambah Pelanggaran Siswa</x-slot>

    <x-slot name="pagePretitle">Menambah Data Pelanggaran Siswa</x-slot>

    <x-slot name="pageTitle">Tambah Pelanggaran Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('on-duty.student-violation.index')" />
    </x-slot>

    <x-alert />

    <div class="row">
        <div class="col-12 col-lg-6">
            @unless ($this->siswa)

                @unless (session()->has('alert'))
                    <div class="alert alert-warning alert-dismissible bg-white" role="alert">
                        <div class="d-flex">
                            <div class="me-3">
                                <h1 class="text-warning las la-exclamation-triangle"></h1>
                            </div>

                            <div>
                                <h4 class="alert-title">Pilih Siswa</h4>
                                <div class="text-muted">Anda belum memilih siswa, pilih siswa yang melanggar...</div>
                            </div>
                        </div>

                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endunless

                <div class="card mb-3">
                    <div class="card-body">
                        <h3><span class="las la-filter fs-2 me-1 text-warning"></span> Cari Berdasarkan</h3>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <x-form.input wire:model.live="filters.search" name="filters.search" type="text"
                                    label="Nama Siswa" placeholder="Cari nama..." />
                            </div>

                            <div class="col-12 col-lg-6">
                                <x-form.select wire:model.lazy="filters.kelas" name="filters.kelas" label="Kelas">
                                    <option value="">- semua kelas -</option>
                                    @foreach ($this->grades as $grade)
                                        <option wire:key="row-{{ $grade->id }}" value="{{ $grade->id }}">
                                            {{ strtoupper($grade->name) }}</option>
                                    @endforeach
                                </x-form.select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex">
                                <div class="ms-auto">
                                    <button wire:click="resetFilters" type="button" class="btn btn-sm">Reset
                                        Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endunless

            <div class="card mb-lg-0 mb-3" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
                <div class="table-responsive mb-0">
                    <table class="table card-table table-bordered datatable">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>

                                <th>Kelas</th>

                                <th>Jenis Kelamin</th>

                                <th style="width: 10px"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($this->students as $row)
                                <tr wire:key="row-{{ $row->id }}">
                                    <td>{{ $row->name ?? '-' }}</td>

                                    <td>{{ $row->grade->name ?? '-' }}</td>

                                    <td>{{ $row->sex ?? '-' }}</td>

                                    <td>
                                        @if ($this->siswa)
                                            <button wire:click="cancelStudent" type="button"
                                                class="btn">Batal</button>
                                        @else
                                            <button wire:click="chooseStudent({{ $row->id }})" type="button"
                                                class="btn btn-primary">Pilih</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <x-datatable.empty colspan="10" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($this->siswa)
                    <div class="p-3">
                        <button wire:click="cancelStudent" type="button" class="btn w-100">Reset Siswa / Pilih
                            Ulang</button>
                    </div>
                @endif

                @unless ($this->siswa)
                    {{ $this->students->links() }}
                @endunless
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <form class="card" wire:submit.prevent="save" autocomplete="off">
                <div class="card-header">
                    Tambah data pelanggaran siswa
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-form.select wire:model="guru" name="guru" label="Guru Piket" required autofocus>
                                <option value="">- pilih guru -</option>
                                @foreach ($this->teachers as $teacher)
                                    <option wire:key="row-{{ $teacher->id }}" value="{{ $teacher->id }}">
                                        {{ ucwords($teacher->name) }}</option>
                                @endforeach
                            </x-form.select>

                            <div class="form-group mb-3">
                                <label class="form-label mb-3" for="jenisPelanggaran">Jenis Pelanggaran</label>
                                @foreach ($this->violationTypes as $type)
                                    <x-form.check wire:model="jenisPelanggaran" name="jenisPelanggaran" type="radio"
                                        required description="{{ ucwords($type->name) }}"
                                        value="{{ $type->name }}" />
                                @endforeach
                            </div>

                            <x-form.textarea wire:model="deskripsi" name="deskripsi"
                                label="Deskripsi / Keterangan Pelanggaran"
                                placeholder="Masukkan deskripsi tentang pelanggaran yang dilakukan"
                                optional="Abaikan jika tidak ingin mengisi" />
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="btn-list justify-content-end">
                        <button type="reset" class="btn">Reset</button>

                        <x-datatable.button.save target="save" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
