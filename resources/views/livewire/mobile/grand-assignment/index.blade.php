<div class="container-fluid">
    <x-mobile.title-page title="Penugasan Kelas" subtitle="Tambah Tugas Kelas." />

    <x-mobile.alert />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-3">
                    <div class="alert alert-border-info mt-3 rounded-2" role="alert">
                        <h6>
                            <i class="ti ti-info-circle f-s-18 me-2 text-info"></i>
                            Daftar Pemberian Tugas Anda.
                        </h6>
                        <a href="{{ route('mobile.grand-assignment.create') }}" class="btn btn-primary-dark">Tambah
                            Tugas</a>
                    </div>

                    <div>
                        <x-form.input wire:model.live="filters.search" name="filters.search"
                            placeholder="Cari Nama Kelas..." type="text" />
                    </div>

                    <div class="table-responsive app-scroll app-datatable-default">
                        <table class="w-100 display ticket-app-table" id="ticketdatatable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Kelas</th>
                                    <th class="text-center">Mapel</th>
                                    <th class="text-center">Status Pemberian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="ticket_key_body">
                                @forelse ($this->grade_assignments as $assignment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ $assignment->grade->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $assignment->school_subject->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $assignment->status ? 'Sudah' : 'Belum' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="px-3 py-4 ms-auto">
                                                <a href="{{ route('mobile.grand-assignment.edit', $assignment->id) }}"
                                                    class="btn btn-sm btn-primary-dark mb-2"
                                                    style="width: 100px"><small>Sunting</small></a>
                                                <button wire:confirm="Apakah anda yakin ingin menghapus data ini?"
                                                    style="width: 100px" wire:click="deleteData({{ $assignment->id }})"
                                                    type="button"
                                                    class="btn btn-sm btn-danger"><small>Hapus</small></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-mobile.empty-data />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
