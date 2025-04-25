<div>
    <x-slot name="title">Status Petugas Piket</x-slot>

    <x-slot name="pageTitle">Status Petugas Piket</x-slot>

    <x-slot name="pagePretitle">Status Petugas Piket</x-slot>

    <x-alert />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-6 d-flex">
            <div class="w-50">
                <x-datatable.search placeholder="Cari nama guru..." />
            </div>

            <div class="w-50 ms-2">
                <x-form.select wire:model.live="filters.status" name="filters.status" form-group-class>
                    <option value="">- semua status piket -</option>
                    <option value="piket">Piket</option>
                    <option value="tidak piket">Tidak Piket</option>
                </x-form.select>
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-3">
            <x-datatable.items-per-page />

            <button wire:click="changeDutyStatusMany" {{ count($this->selected) == 0 ? 'disabled' : '' }}
                class="btn btn-green" type="button">Aktifkan
                {{ count($this->selected) ?? 0 }} Guru Piket</button>

            <button wire:click="muatUlang" class="btn btn-dark py-1 ms-2"><span
                    class="las la-redo-alt fs-1"></span></button>
        </div>
    </div>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Nama" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Nomor Ponsel" wire:click="sortBy('phone_number')"
                                :direction="$sorts['phone_number'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Jenis Kelamin" wire:click="sortBy('sex')"
                                :direction="$sorts['sex'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Status Piket" wire:click="sortBy('duty_status')"
                                :direction="$sorts['duty_status'] ?? null" />
                        </th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> guru,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            guru?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> guru.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm px-3"
                                        style="background-image: url({{ $row->user->avatarUrl() }})"></span>

                                    <span class="ms-2">{{ $row->name }}</span>
                                </div>
                            </td>

                            <td>{{ $row->phone_number ?? '-' }}</td>

                            <td>{{ ucwords($row->sex ?? '-') }}</td>

                            <td>
                                <button wire:click="changeDutyStatus({{ $row->id }})"
                                    class="btn btn btn-{{ $row->duty_status ? 'success' : 'dark' }}" type="button">
                                    {{ $row->duty_status ? 'Piket' : 'Tidak Piket' }}
                                    <span class="las la-sync fs-1 ms-1"></span>
                                </button>
                            </td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm" href="{{ route('teacher.edit', $row->id) }}">
                                            Sunting
                                        </a>
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
