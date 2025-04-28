@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
@endpush

<div>
    <x-slot name="title">Tambah Penugasan Kelas</x-slot>

    <x-slot name="pagePretitle">Menambah Data Penugasan Kelas</x-slot>

    <x-slot name="pageTitle">Tambah Penugasan Kelas</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('on-duty.grade-assignment.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="save" autocomplete="off">
        <div class="card-header">
            Tambah data penugasan kelas
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.select wire:model.lazy="kelas" name="kelas" label="Kelas" autofocus required>
                        <option value="">- pilih kelas -</option>
                        @foreach ($this->grades as $grade)
                            <option wire:key="row-{{ $grade->id }}" value="{{ $grade->id }}">
                                {{ strtoupper($grade->name) }}</option>
                        @endforeach
                    </x-form.select>

                    @if ($this->kelas)
                        <x-form.select wire:model.lazy="guru" name="guru" label="Guru" required>
                            <option value="">- pilih guru -</option>
                            @foreach ($this->teachers as $teacher)
                                <option wire:key="row-{{ $teacher->id }}" value="{{ $teacher->id }}">
                                    {{ strtoupper($teacher->name) }}</option>
                            @endforeach
                        </x-form.select>
                    @endif

                    @if ($this->guru)
                        <x-form.select wire:model.lazy="mataPelajaran" name="mataPelajaran" label="Mata Pelajaran"
                            required>
                            <option value="">- pilih mata pelajaran -</option>
                            @foreach ($this->subjects as $subject)
                                <option wire:key="row-{{ $subject->id }}" value="{{ $subject->id }}">
                                    {{ strtoupper($subject->name) }}</option>
                            @endforeach
                        </x-form.select>
                    @endif
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.textarea wire:model="alasanGuru" name="alasanGuru" label="Alasan Guru Tidak Masuk Kelas"
                        placeholder="Masukkan alasan guru tidak masuk kelas." required />
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="tanggalMulai" name="tanggalMulai" label="Tanggal Mulai / Waktu Mulai"
                        placeholder="masukkan tanggal mulai" type="datetime-local" required />

                    <x-form.input wire:model="tanggalSelesai" name="tanggalSelesai"
                        label="Tanggal Selesai / Waktu Selesai" placeholder="masukkan tanggal selesai"
                        type="datetime-local" required />

                    <x-form.input wire:model="fileTugas" name="fileTugas" label="File Tugas (Jika Ada)" type="file"
                        optional="Masukkan jika ada file tugas ingin di sisipkan" />
                </div>

                <div class="col-12 col-lg-6" wire:ignore>
                    <x-form.textarea id="keteranganTugas" wire:model="keteranganTugas" name="keteranganTugas"
                        label="Deskirpsi / Keterangan Tugas" placeholder="Masukkan keterangan tugas jika ada."
                        required />
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-list justify-content-end">
                <button type="reset" class="btn">Reset</button>

                <x-datatable.button.save target="save" />
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/ko.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editor = SUNEDITOR.create(document.getElementById('keteranganTugas') || 'sample', {
                height: 200,
                defaultStyle: "font-family: Times New Roman; font-size: 12px;",
                font: ['Times New Roman', 'Arial', 'Verdana', 'Tahoma', 'Courier New'],
                buttonList: [
                    ['font', 'fontSize', 'formatBlock', 'list', 'align', 'horizontalRule', 'lineHeight',
                        'undo', 'redo', 'bold', 'italic', 'underline'
                    ],
                ],
                lang: SUNEDITOR_LANG['en']
            });

            editor.onChange = function(contents) {
                @this.set('keteranganTugas', contents);
            };

            Livewire.on('changeKeteranganTugas', function(content) {
                editor.setContents(content[0]);
            });

            Livewire.on('clearDataKeteranganTugas', function(content) {
                editor.setContents(content[0]);
            })
        });
    </script>
@endpush
