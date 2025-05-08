<div>
    <x-mobile.alert />

    <form wire:submit="edit" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <x-form.input wire:model="nama" name="nama" label="Nama Anda" type="name"
                        placeholder="Masukkan Nama Anda" />

                    <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel" type="name"
                        placeholder="08XXXXXXXX" />

                    <x-form.textarea wire:model="alamat" name="alamat" label="Alamat" type="name"
                        placeholder="Masukkan Alamat Anda" />

                    <div class="form-group">
                        <label class="form-label" for="jenisKelamin">Jenis Kelamin</label>

                        @foreach (config('const.sex') as $sex)
                            <x-form.check wire:key="row-{{ $sex }}" wire:model="jenisKelamin"
                                name="jenisKelamin" value="{{ $sex }}" type="radio"
                                description="{{ $sex }}" />
                        @endforeach
                    </div>
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
