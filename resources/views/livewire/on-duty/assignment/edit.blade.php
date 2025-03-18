<div>
    <x-slot name="title">Tambah Penugasan Piket Siswa</x-slot>

    <x-slot name="pagePretitle">Tambah Penugasan Piket Siswa</x-slot>

    <x-slot name="pageTitle">Tambah Penugasan Piket Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('on-duty.assignment.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Tambah data penugasan piket siswa
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.select wire:model.lazy="siswa" name="siswa" label="Pilih Siswa" required autofocus>
                        <option value="">- pilih siswa -</option>
                        @foreach ($this->students as $student)
                            <option wire:key="{{ $student->id }}" value="{{ $student->id }}">{{ $student->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    @if ($this->siswa)
                        <x-form.select wire:model.lazy="mataPelajaran" name="mataPelajaran" label="Pilih Mata Pelajaran"
                            required>
                            <option value="">- pilih mata pelajaran -</option>
                            @foreach ($this->school_subjects as $mapel)
                                <option wire:key="{{ $mapel->id }}" value="{{ $mapel->id }}">{{ $mapel->name }} |
                                    {{ $mapel->code }}
                                </option>
                            @endforeach
                        </x-form.select>
                    @endif

                    @if ($this->mataPelajaran)
                        <x-form.select wire:model.lazy="guru" name="guru" label="Guru / Pendidik Yang Bersangkutan"
                            optional="Abaikan jika tidak ingin mengubah pendidik." required>
                            <option value="">- pilih guru -</option>
                            @foreach ($this->teachers as $teacher)
                                <option wire:key="{{ $teacher->id }}" value="{{ $teacher->id }}">
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </x-form.select>
                    @endif
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.textarea wire:model="deskripsi" name="deskripsi" label="Deskripsi / Keterangan Piket"
                        required />
                </div>
            </div>
        </div>

        @if ($this->mataPelajaran)
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <x-form.input wire:model="jadwalPelaksanaan" name="jadwalPelaksanaan" label="Jadwal Pelaksanaan"
                            type="datetime-local" />

                        <x-form.input wire:model="jadwalSelesai" name="jadwalSelesai" label="Waktu Selesai"
                            type="datetime-local" />
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-3" for="jenisPelanggaran">Jenis Pelanggaran</label>
                        @foreach (config('const.violation_type') as $violation)
                            <x-form.check wire:model="jenisPelanggaran" name="jenisPelanggaran" type="radio"
                                description="{{ ucwords($violation) }}" value="{{ $violation }}" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        @if ($this->dokumentasiSiswa)
                            <img class="rounded-4 object-fit-cover" style="width: 100%; height: 200px"
                                src="{{ $this->dokumentasiSiswa->temporaryUrl() }}" alt="logo">
                        @else
                            <img class="rounded-4 object-fit-contain"
                                style="width: 100%; height: 200px; background-color: #eeeeee"
                                src="{{ $this->photoStudentUrl }}" alt="logo-default">
                        @endif

                        <div class="mt-3">
                            <x-form.input wire:model="dokumentasiSiswa" name="dokumentasiSiswa"
                                label="Dokumentasi Siswa" type="file"
                                optional="Abaikan jika tidak ingin mengubah." />
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        @if ($this->dokumentasiKegiatan)
                            <img class="rounded-4 object-fit-cover" style="width: 100%; height: 200px"
                                src="{{ $this->dokumentasiKegiatan->temporaryUrl() }}" alt="logo">
                        @else
                            <img class="rounded-4 object-fit-contain"
                                style="width: 100%; height: 200px; background-color: #eeeeee"
                                src="{{ $this->dokumentasiKegiatanUrl }}" alt="logo-default">
                        @endif

                        <div class="mt-3">
                            <x-form.input wire:model="dokumentasiKegiatan" name="dokumentasiKegiatan"
                                label="Dokumentasi Kegiatan" type="file"
                                optional="Abaikan jika tidak ingin mengubah." />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-footer">
            <div class="btn-list justify-content-end">
                <button type="reset" class="btn">Reset</button>

                <x-datatable.button.save target="edit" />
            </div>
        </div>
    </form>
</div>
