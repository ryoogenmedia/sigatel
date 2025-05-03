<div class="container-fluid">
    <x-mobile.title-page title="Akun" subtitle="Kelola data akun anda." />

    <x-mobile.alert />

    <form wire:submit="edit" class="card">
        <div class="card-body">
            <div class="row mx-2 mt-3 mb-5">
                <div class="col-12">
                    <div class="row">
                        <div
                            class="col-lg-2 align-self-center text-lg-start text-center mt-lg-0 mt-4 mb-lg-0 mb-2 mb-lg-3">
                            @if ($avatar)
                                <a aria-controls="profilecanvasRight" class="d-block head-icon"
                                    data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#"
                                    role="button">
                                    <img alt="avtar" class="b-r-50 h-90 w-90 bg-dark"
                                        src="{{ $avatar->temporaryUrl() }}">
                                </a>
                            @else
                                <a aria-controls="profilecanvasRight" class="d-block head-icon"
                                    data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#"
                                    role="button">
                                    <img alt="avtar" class="b-r-50 h-90 w-90 bg-dark" src="{{ $avatarUrl }}">
                                </a>
                            @endif
                        </div>

                        <div class="col-lg-auto col-12 mt-3 mt-lg-0">
                            <x-form.input wire:model.lazy="avatar" name="avatar" label="Avatar" type="file"
                                optional="Abaikan jika tidak ingin mengubah." />
                        </div>
                    </div>

                    <x-form.input wire:model="username" name="username" label="Username" type="text"
                        placeholder="masukkan username" autofocus />

                    <x-form.input wire:model="surel" name="surel" label="Alamat Surel (email)"
                        placeholder="contoh@email.com" type="email" />

                    <x-form.input wire:model.lazy="kataSandi" name="kataSandi" label="Kata Sandi (Password)"
                        placeholder="******" type="password" form-group-class />

                    <x-form.input wire:model.lazy="konfirmasiKataSandi" name="konfirmasiKataSandi"
                        label="Konfirmasi Kata Sandi (Password)" placeholder="******" type="password"
                        form-group-class />
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
