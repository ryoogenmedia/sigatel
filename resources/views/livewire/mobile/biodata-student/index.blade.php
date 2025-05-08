<div class="container-fluid">
    <x-mobile.title-page title="Biodata Anak" subtitle="Kelola data biodata anak anda." />

    <x-mobile.alert />

    <form wire:submit="edit" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <x-form.input wire:model="nama" name="nama" label="Nama" type="text"
                        placeholder="Masukkan nama lengkap anak anda" />

                    <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="nomorPonsel" type="text"
                        placeholder="Masukkan nomor ponsel" />

                    <div class="form-control my-3">
                        <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>

                        @foreach (config('const.sex') as $sex)
                            <x-form.check wire:key="row-{{ $sex }}" wire:model="jenisKelamin"
                                name="jenisKelamin" value="{{ $sex }}" type="radio"
                                description="{{ $sex }}" />
                        @endforeach
                    </div>

                    <x-form.textarea wire:model="alamat" name="alamat" placeholder="Masukkan alamat lengkap" />

                    <x-form.input wire:model="nis" name="nis" type="text" placeholder="Masukkan NIS" />
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="w-100">
                <x-datatable.button.save target="edit" name="Simpan" class="btn btn-primary-dark w-100" />
            </div>
        </div>
    </form>

</div>
