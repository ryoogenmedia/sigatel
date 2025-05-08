<div>
    <x-mobile.alert />

    <form wire:submit="edit" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <x-form.input wire:model="nama" name="nama" label="Nama Anda" type="name"
                        placeholder="Masukkan Nama Anda" />

                    <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel" type="name"
                        placeholder="Masukkan Nomor Ponsel Anda" />

                    <x-form.select wire:model="statusOrangTua" name="statusOrangTua" label="Status Orang Tua">
                        <option value="">- pilih status orang tua -</option>

                        @foreach (config('const.guardian_status') as $status)
                            <option wire:key="row-{{ $status }}" value="{{ $status }}">
                                {{ ucwords($status) }}</option>
                        @endforeach
                    </x-form.select>
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
