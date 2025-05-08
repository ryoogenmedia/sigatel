<div class="container-fluid">
    <x-mobile.title-page title="Daftar Pelanggaran Anak Anda" subtitle="Lihat Pelanggaran Anak." />

    <div class="row">
        <div class="col-12">
            <div class="card">
                @if (count($this->rows) >= 1)
                    <div class="card-header">
                        <h5 class="header-title-text">Timeline Pelanggaran</h5>
                    </div>
                @endif

                <div class="card-body">
                    <ul class="app-timeline-box">
                        @forelse ($this->rows as $row)
                            <li class="timeline-section">
                                <div class="timeline-icon">
                                    <span class="h-35 w-35 d-flex-center b-r-50">
                                        <img class="h-30 w-30 rounded-5" src="{{ $row->teacher->user->avatarUrl() }}"
                                            alt="gambar">
                                    </span>
                                </div>
                                <div class="timeline-content pt-0 ">
                                    <div class="d-flex f-s-16">
                                        <p class="text-success f-s-16 mb-0">Dari Guru : {{ $row->teacher->name ?? '-' }}
                                    </div>
                                    <p class="">
                                        Tanggal / Waktu : {{ $row->time_violation ?? '' }}
                                    </p>
                                    <div class="timeline-border-box me-2 ms-0 mt-3">
                                        <h6 class="mb-0">{{ ucwords($row->violation_type) ?? '-' }}
                                        </h6>
                                        <div class="text-secondary">{!! $row->description ?? '' !!}</div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="hidden-massage py-4 px-3">
                                <div class="bg-white py-3">
                                    <div class="d-flex">
                                        <img class="mx-auto my-3" style="width: 220px;"
                                            src="{{ asset('ryoogen/illustration/good-student.jpg') }}"
                                            alt="illustration">
                                    </div>
                                    <h6 class="mb-0 text-center">Pelanggaran Belum Ada</h6>
                                    <p class="text-secondary text-center">Anak anda belum pernah melakukan pelanggaran.
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('mobile/assets/js/project_details.js') }}"></script>
@endpush
