<div>
    <x-slot name="title">Sunting Pengguna</x-slot>

    <x-slot name="pagePretitle">Menyunting Data Pengguna</x-slot>

    <x-slot name="pageTitle">Sunting Pengguna</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('user.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Sunting data pengguna
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
                    <x-form.input wire:model="username" name="username" label="Nama Pengguna (Username)"
                        placeholder="masukkan username" type="text" />
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="w-100 me-2">
                        <x-form.input wire:model="email" name="email" label="Masukkan Email"
                            placeholder="masukkan email" type="text" />
                        <x-form.select form-group-class wire:model.lazy="roles" name="roles" label="Level">
                            <option value="">- pilih pengguna -</option>
                            @foreach (config('const.roles') as $role)
                                <option value="{{ $role }}">{{ ucwords($role) }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="password" name="password" label="Password" placeholder="**********"
                        type="password" />
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
