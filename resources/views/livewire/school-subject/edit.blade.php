<div>
    <x-slot name="title">Sunting Mata Pelajaran</x-slot>

    <x-slot name="pagePretitle">Menyunting Data Mata Pelajaran</x-slot>

    <x-slot name="pageTitle">Sunting Mata Pelajaran</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('school-subject.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Sunting data mata pelajaran
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="namaMataPelajaran" name="namaMataPelajaran" label="Nama Mata Pelajaran"
                        type="text" placeholder="Masukkan nama mata pelajaran" required autofocus />

                    <x-form.input wire:model="kode" name="kode" label="Kode Mata Pelajaran" type="string"
                        placeholder="MAT001" required />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.select wire:model="pendidik" name="pendidik" label="Pendidik">
                        <option value="">- pilih pendidik -</option>
                        @foreach ($this->teachers as $teacher)
                            <option wire:key="{{ $teacher->id }}" value="{{ $teacher->id }}">{{ $teacher->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.toggle wire:model="status" name="status" label="Status Mata Kuliah" />
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
