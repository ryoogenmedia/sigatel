<div>
    <x-slot name="title">Masukan / Evaluasi Siswa Piket</x-slot>

    <x-slot name="pageTitle">Masukan / Evaluasi Siswa Piket</x-slot>

    <x-slot name="pagePretitle">Berikan Masukan / Evaluasi Untuk Siswa Piket</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <x-modal :show="$this->showModal">
        <form wire:submit.prevent="changeStatusOnDuty" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Status Piket</h5>
                <button wire:click='closeModal' type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <x-form.select wire:model.lazy="statusOnDuty" name="statusOnDuty" label="Status Piket">
                            <option value="">- pilih status -</option>
                            @foreach (config('const.duty_status') as $status)
                                <option wire:key="{{ $status }}" value="{{ $status }}">
                                    {{ ucwords($status) }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-list justify-content-end">
                    <button wire:click="resetForm" type="reset" class="btn">Reset</button>

                    <x-datatable.button.save target="changeStatusOnDuty" name="Simpan Perubahan" class="btn-green" />
                </div>
            </div>
        </form>
    </x-modal>

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-8 d-flex align-self-center">
            <div>
                <x-datatable.search placeholder="Cari nama siswa..." />
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="assignment-duty" />
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
        </div>
    </div>

    <x-datatable.filter.card id="assignment-duty">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.scheduleTime" name="filters.scheduleTime" type="datetime-local"
                    label="Tanggal & Waktu Penugasan" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.input wire:model.live="filters.finishTime" name="filters.finishTime" type="datetime-local"
                    label="Tanggal & Waktu Selesai" />
            </div>

            <div class="col-12">
                <x-form.select wire:model.live="filters.category" name="filters.category" label="Kategori Penugasan">
                    <option value="">- semua kategori -</option>
                    @foreach (config('const.violation_type') as $category)
                        <option wire:key="{{ $category }}" value="{{ $category }}">{{ ucwords($category) }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>
        </div>
    </x-datatable.filter.card>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th style="width: 200px">Siswa</th>

                        <th>
                            <x-datatable.column-sort name="Status" wire:click="sortBy('status')" :direction="$sorts['status'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Kategori Penugasan" wire:click="sortBy('violation_type')"
                                :direction="$sorts['violation_type'] ?? null" />
                        </th>

                        <th style="width: 10px">Jadwal Penugasan</th>

                        <th>Pemberi Tugas Piket / Guru</th>

                        <th>Kaitan Mata Pelajaran</th>

                        <th style="width: 10px"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="10" class="bg-green-lt">
                            <div class="text-green d-flex gap-2">
                                <x-form.toggle wire:model.lazy="checkedStatus" name="checkedStatus"
                                    form-group-class="mb-0" />
                                <div class="mb-0 mt-2">Tampilkan status yang telah selesai!</div>
                            </div>
                        </td>
                    </tr>

                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm px-3"
                                        style="background-image: url({{ $row->student->user->avatarUrl() }})"></span>

                                    <span class="ms-2">{{ $row->student->name }}</span>
                                </div>
                            </td>

                            <td>
                                <span @class([
                                    'badge',
                                    'bg-lime' => $row->status == 'approved',
                                    'bg-orange' => $row->status == 'pending',
                                    'bg-red' => $row->status == 'reject',
                                ])>{{ $row->status }}</span>
                            </td>

                            <td>{{ ucwords($row->violation_type ?? '-') }}</td>

                            <td>
                                <span class="badge bg-green-lt me-2 mb-2">{{ $row->jadwal_penugasan }}</span>
                                <span class="badge bg-red-lt">{{ $row->jadwal_selesai }}</span>
                            </td>

                            <td>{{ $row->teacher->name ?? '-' }}</td>

                            <td>
                                <p class="mb-1">{{ $row->school_subject->name ?? '-' }}</p>
                                <span class="badge bg-dark">{{ $row->school_subject->code }}</span>
                            </td>

                            <td>
                                <div class="d-flex gap-1">
                                    <button wire:click="openModal({{ $row->id }})" class="btn btn-sm btn-blue">
                                        <span class="las la-redo-alt fs-1"></span>
                                    </button>

                                    <a class="btn btn-sm btn-success"
                                        href="{{ route('on-duty.feedback.message', $row->id) }}">
                                        <span class="las la-comment fs-1"></span>
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
