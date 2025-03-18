<div>
    <x-alert />

    <div class="row">
        <div class="col">
            <form class="card" wire:submit.prevent="edit" autocomplete="off">
                <div class="card-header">Profil Sekolah</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <x-form.input wire:model="namaSekolah" name="namaSekolah" label="Nama Sekolah" type="text"
                                placeholder="masukkan nama sekolah" autofocus />
                            <x-form.input wire:model="emailSekolah" name="emailSekolah" label="Email Sekolah"
                                type="text" placeholder="masukkan email sekolah" />
                            <x-form.input wire:model="nomorTelepon" name="nomorTelepon" label="Nomor Telepon"
                                type="text" placeholder="masukkan nomor telepon" />
                            <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel"
                                type="text" placeholder="masukkan nomor ponsel" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <x-form.textarea wire:model="alamatSekolah" name="alamatSekolah" label="Alamat Sekolah" />

                            <div class="row">
                                <div class="col-6">
                                    <x-form.input wire:model="kodePos" name="kodePos" label="Kode Pos" type="text"
                                        placeholder="9999" />
                                </div>

                                <div class="col-6">
                                    <x-form.input wire:model="akreditasSekolah" name="akreditasSekolah"
                                        label="Akreditas Sekolah" type="text" placeholder="A" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <h3>Logo Sekolah</h3>

                            @if ($this->logoSekolah)
                                <img class="rounded-4 object-fit-cover" style="width: 100%; height: 200px"
                                    src="{{ $this->logoSekolah->temporaryUrl() }}" alt="logo">
                            @else
                                <img style="width: 100%; height: 200px" src="{{ $this->logoUrl }}" alt="logo-default">
                            @endif

                            <div class="mt-3">
                                <x-form.input wire:model.lazy="logoSekolah" name="logoSekolah" type="file" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="btn-list justify-content-end">
                        <button type="reset" class="btn">Reset</button>

                        <x-datatable.button.save name="Simpan Perubahan" target="edit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
