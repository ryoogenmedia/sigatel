<div>
    <x-slot name="title">Pesan Masukan Siswa Piket</x-slot>

    <x-slot name="pageTitle">Pesan Masukan Siswa Piket</x-slot>

    <x-slot name="pagePretitle">Berikan Pesan Masukan Siswa Piket</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <x-datatable.search placeholder="Cari..." />
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-3">
            <x-datatable.items-per-page />

            <x-datatable.bulk.dropdown>
                <div class="dropdown-menu dropdown-menu-end datatable-dropdown">
                    <button data-bs-toggle="modal" data-bs-target="#delete-confirmation" class="dropdown-item"
                        type="button">
                        <i class="las la-trash me-3"></i>

                        <span>Hapus</span>
                    </button>
                </div>
            </x-datatable.bulk.dropdown>

            <button wire:click="muatUlang" class="btn btn-dark py-1 ms-2"><span
                    class="las la-redo-alt fs-1"></span></button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-4">
            <form class="card mb-3" wire:submit.prevent="save" autocomplete="off">
                <div class="card-header">
                    {{ $this->feedBackId ? 'Sunting' : 'Tambah' }} Data
                </div>

                <div class="card-body">
                    <x-form.textarea wire:model="pesan" name="pesan" label="Pesan / Komentar / Masukan" />
                </div>

                <div class="card-footer">
                    <div class="btn-list justify-content-end">
                        <button wire:click="resetData" type="reset" class="btn">Reset</button>

                        <x-datatable.button.save target="save"
                            name="{{ $this->feedBackId ? 'Sunting' : 'Tambah' }} Data"
                            class="btn-{{ $this->feedBackId ? 'orange' : 'green' }}" />
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
                <div class="table-responsive mb-0">
                    <table class="table card-table table-bordered datatable">
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <x-datatable.bulk.check wire:model.lazy="selectPage" />
                                </th>

                                <th>Komentar / Pesan / Masukan</th>

                                <th style="width: 10px"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($selectPage)
                                <tr>
                                    <td colspan="10" class="bg-green-lt">
                                        @if (!$selectAll)
                                            <div class="text-green">
                                                <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong>
                                                    masukan piket siswa,
                                                    apakah
                                                    Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                                    masukan piket siswa?</span>

                                                <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                            </div>
                                        @else
                                            <span class="text-pink">Anda sekarang memilih semua
                                                <strong>{{ count($this->selected) }}</strong> masukan piket siswa.
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            @forelse ($this->rows as $row)
                                <tr wire:key="row-{{ $row->id }}">
                                    <td>
                                        <x-datatable.bulk.check wire:model.lazy="selected"
                                            value="{{ $row->id }}" />
                                    </td>

                                    <td>{{ $row->comment }}</td>

                                    <td>
                                        <div class="d-flex">
                                            <div class="ms-auto">
                                                <button wire:click="suntingData({{ $row->id }})"
                                                    class="btn btn-sm">
                                                    Sunting
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-datatable.empty colspan="10" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $this->rows->links() }}
            </div>
        </div>
    </div>
</div>
