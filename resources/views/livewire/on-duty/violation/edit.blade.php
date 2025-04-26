<div>
    <x-slot name="title">Sunting Pelanggaran Siswa</x-slot>

    <x-slot name="pagePretitle">Menyunting Data Pelanggaran Siswa</x-slot>

    <x-slot name="pageTitle">Sunting Pelanggaran Siswa</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('on-duty.student-violation.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="edit" autocomplete="off">
        <div class="card-header">
            Sunting data pelanggaran siswa
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.select wire:model="guru" name="guru" label="Guru Piket" required autofocus>
                        <option value="">- pilih guru -</option>
                        @foreach ($this->teachers as $teacher)
                            <option wire:key="row-{{ $teacher->id }}" value="{{ $teacher->id }}">
                                {{ ucwords($teacher->name) }}</option>
                        @endforeach
                    </x-form.select>

                    <div class="form-group mb-3">
                        <label class="form-label mb-3" for="jenisPelanggaran">Jenis Pelanggaran</label>
                        @foreach ($this->violationTypes as $type)
                            <x-form.check wire:model="jenisPelanggaran" name="jenisPelanggaran" type="radio" required
                                description="{{ ucwords($type->name) }}" value="{{ $type->name }}" />
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.textarea wire:model="deskripsi" name="deskripsi" label="Deskripsi / Keterangan Pelanggaran"
                        placeholder="Masukkan deskripsi tentang pelanggaran yang dilakukan"
                        optional="Abaikan jika tidak ingin mengisi" />
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
