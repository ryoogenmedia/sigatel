<div>
    <x-slot name="title">Sunting Orang Tua Siswa</x-slot>

    <x-slot name="pagePretitle">Menyunting Data Orang Tua Siswa</x-slot>

    <x-slot name="pageTitle">Sunting Orang Tua Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('guardian-parent.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Sunting data orang tua siswa
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="namaOrangTuaSiswa" name="namaOrangTuaSiswa" label="Nama Orang Tua Siswa"
                        type="text" placeholder="Masukkan nama orang tua siswa" required autofocus />

                    <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel" type="string"
                        placeholder="08xxxxxxx" required autofocus />
                </div>

                <div class="col-12 col-lg-6">
                    <div class="mb-0">
                        <label for="statusHubungan" class="form-label">Hubungan Dengan Anak</label>
                        <div class="d-flex gap-3 mt-3">
                            @foreach (config('const.guardian_status') as $status)
                                <x-form.check wire:model="statusHubungan" name="statusHubungan" type="radio"
                                    value="{{ $status }}" description="{{ $status }}" />
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-form.select wire:model="siswa" name="siswa" label="Siswa">
                            <option value="">- pilih siswa -</option>
                            @foreach ($this->students as $student)
                                <option wire:key="{{ $student->id }}" value="{{ $student->id }}">{{ $student->name }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>
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
