<div>
    <x-slot name="title">Penugasan Kelas</x-slot>

    <x-slot name="pageTitle">Penugasan Kelas</x-slot>

    <x-slot name="pagePretitle">Kelola Data Penugasan Kelas</x-slot>

    <x-slot name="button">
        <x-datatable.button.add name="Tambah Penugasan Kelas" :route="route('on-duty.grade-assignment.create')" />
    </x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <div>
                <x-datatable.search placeholder="Cari nama kelas / guru / mapel..." />
            </div>

            <div class="ms-2">
                <x-form.select wire:model.lazy="filters.status" name="filters.status" form-group-class>
                    <option value="">- status pemberian tugas -</option>
                    <option value="sudah">Sudah Diberikan</option>
                    <option value="belum">Belum Diberikan</option>
                </x-form.select>
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="grade-assignment" />
            </div>
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

    <x-datatable.filter.card id="grade-assignment">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.select wire:model.live="filters.grade" name="filters.grade" label="Kelas">
                    <option value="">- semua kelas -</option>
                    @foreach ($this->grades as $grade)
                        <option wire:key="row-{{ $grade->id }}" value="{{ $grade->id }}">
                            {{ strtoupper($grade->name) }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select wire:model.live="filters.school_subject" name="filters.school_subject"
                    label="Mata Pelajaran">
                    <option value="">- semua mata pelajaran -</option>
                    @foreach ($this->subjects as $subject)
                        <option wire:key="row-{{ $subject->id }}" value="{{ $subject->id }}">
                            {{ strtoupper($subject->name) }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="col-12 col-lg-6">
                <x-form.select wire:model.live="filters.teacher" name="filters.teacher" label="Guru">
                    <option value="">- semua guru -</option>
                    @foreach ($this->teachers as $teacher)
                        <option wire:key="row-{{ $teacher->id }}" value="{{ $teacher->id }}">
                            {{ strtoupper($teacher->name) }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select wire:model.live="filters.time" name="filters.time" label="Waktu Pemberian Tugas">
                    <option value="">- semua waktu -</option>
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_week">Minggu Ini</option>
                    <option value="last_week">Minggu Kemarin</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                </x-form.select>
            </div>
        </div>
    </x-datatable.filter.card>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>

                        <th class="text-center">Kelas</th>

                        <th>Guru Pemberi Tugas</th>

                        <th>Mata Pelajaran</th>

                        <th>
                            <x-datatable.column-sort name="Status" wire:click="sortBy('status')" :direction="$sorts['status'] ?? null" />
                        </th>

                        <th>Waktu Pemberian - Selesai</th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-green-lt">
                                @if (!$selectAll)
                                    <div class="text-green">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> penugasan
                                            kelas,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            penugasan kelas?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> penugasan kelas.
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

                            <td class="text-center"><b>{{ $row->grade->name ?? '-' }}</b></td>

                            <td>{{ $row->teacher->name ?? '-' }}
                                <div class="d-flex gap-1 mt-1">
                                    <button type="button" class="btn btn-primary btn-sm">Dokumentasi <span
                                            class="las la-camera ms-1"></span></button>
                                    @if ($row->file_assignment)
                                        <a target="_blank" href="{{ asset('storage/' . $row->file_assignment) }}"
                                            type="button" class="btn btn-red btn-sm">File Tugas
                                            <span class="las la-file-alt ms-1"></span>
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <td>{{ $row->school_subject->name ?? '-' }}</td>

                            <td>
                                <div class="badge bg-{{ $row->status ? 'lime' : 'warning' }}">
                                    {{ $row->status ? 'sudah diberikan' : 'belum diberikan' }}</div>
                            </td>


                            <td>
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="badge bg-green-lt">{{ $row->schedule }}</div>
                                    <div>-</div>
                                    <div class="badge bg-blue-lt">{{ $row->finish }}</div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex gap-1">
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('on-duty.grade-assignment.edit', $row->id) }}">
                                        <span class="las la-pencil-alt fs-2"></span>
                                    </a>

                                    <button class="btn btn-sm btn-blue">
                                        <span class="las la-eye fs-2"></span>
                                    </button>

                                    <a class="btn btn-sm btn-{{ $row->longitude && $row->latitude ? 'success' : 'red' }}"
                                        href="{{ route('on-duty.grade-assignment.map', $row->id) }}">
                                        <span class="las la-map-marker fs-2"></span>
                                    </a>
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
