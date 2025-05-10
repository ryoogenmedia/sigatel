<div>
    <x-slot name="title">Sunting Siswa</x-slot>

    <x-slot name="pagePretitle">Sunting Data Siswa</x-slot>

    <x-slot name="pageTitle">Sunting Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('student.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Sunting data siswa
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <x-form.input wire:model="namaSiswa" name="namaSiswa" label="Nama Siswa" type="text"
                                placeholder="Masukkan nama siswa" required autofocus />
                        </div>

                        <div class="col-12 col-lg-6">
                            <x-form.input wire:model="nis" name="nis" label="NIS" type="text"
                                placeholder="Masukkan Nomor Induk Siswa (NIS)" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel"
                                type="text" placeholder="08xxxxxxx" required />
                        </div>

                        <div class="col-12 col-lg-6">
                            <x-form.select wire:model="kelas" name="kelas" label="Kelas" required>
                                <option value="">- pilih kelas -</option>
                                @foreach ($this->grades as $grade)
                                    <option wire:key="{{ $grade->id }}" value="{{ $grade->id }}">
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </x-form.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                            <div class="d-flex gap-3 mt-3">
                                <x-form.check wire:model="jenisKelamin" name="jenisKelamin" type="radio"
                                    value="laki-laki" description="laki-laki" />
                                <x-form.check wire:model="jenisKelamin" name="jenisKelamin" type="radio"
                                    value="perempuan" description="perempuan" />
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <x-form.select wire:model="status" name="status" label="Status Siswa">
                                <option value="">- pilih status -</option>
                                @foreach (config('const.teacher_status') as $status)
                                    <option value="{{ $status }}">{{ ucwords($status) }}</option>
                                @endforeach
                            </x-form.select>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.textarea wire:model="alamat" name="alamat" label="Alamat Lengkap" rows="10" />
                </div>
            </div>
        </div>

        <div class="card-body">
            Sunting data akun
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="row">
                        @if ($this->avatar)
                            <div class="col-lg-2 col-12 mb-lg-0 mb-3 text-center">
                                <span class="avatar avatar-md"
                                    style="background-image: url({{ $this->avatar->temporaryUrl() }})"></span>
                            </div>
                        @else
                            <div class="col-lg-2 col-12 mb-lg-0 mb-3 text-center">
                                <span class="avatar avatar-md"
                                    style="background-image: url({{ $this->avatarUrl }})"></span>
                            </div>
                        @endif

                        <div class="col">
                            <x-form.input wire:model="avatar" name="avatar" label="Foto Profil (Avatar)"
                                placeholder="masukkan avatar" type="file" />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="email" name="email" label="Masukkan Email" placeholder="masukkan email"
                        type="text" required />
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="w-100 me-2">
                        <x-form.input wire:model="kataSandi" name="kataSandi" label="Kata Sandi (Password)"
                            placeholder="**********" type="password" />
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="konfirmasiKataSandi" name="konfirmasiKataSandi"
                        label="Konfirmasi Kata Sandi (Password)" placeholder="**********" type="password" />
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-list justify-content-end">
                <button type="reset" class="btn">Reset</button>

                <x-datatable.button.save target="edit" />
            </div>
        </div>
    </form>
</div>
