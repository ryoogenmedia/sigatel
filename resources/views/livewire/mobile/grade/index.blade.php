<div class="container-fluid mt-3">
    <div class="row ticket-app">

        <div class="col-lg-6">
            <div class="card create-ticket-card">
                <div class="card-body">
                    <div class="col-xl-12">
                        <div class="row align-items-center">
                            <div class="col-sm-7 col-12">
                                <div class="ticket-create">
                                    <h5 class=" mb-2 ">Daftar Siswa Anda.</h5>
                                    <p class="mb-5 mt-3 text-secondary"> Anda Sebagai Wali Kelas, Memiliki Tanggung
                                        Jawab Terhadap Siswa Anda.</p>
                                </div>
                            </div>
                            <div class="col-sm-5 col-12">
                                <img alt="ilustrasi" class="img-fluid w-200 d-block m-auto"
                                    src="{{ asset('mobile/assets/images/icons/ticket.png') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card ticket-card bg-light-primary">
                        <div class="card-body">
                            <i class="ph-bold  ph-circle circle-bg-img"></i>
                            <div class="h-50 w-50 d-flex-center b-r-15 bg-white mb-3">
                                <i class="ph-bold  ph-graduation-cap f-s-25 text-primary"></i>
                            </div>
                            <p class="f-s-16">Total Siswa Kelas Anda</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="text-primary-dark">{{ $totalSiswa ?? '0' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card ticket-card bg-light-info">
                        <div class="card-body">
                            <i class="ph-bold  ph-circle circle-bg-img"></i>
                            <div class="h-50 w-50 d-flex-center b-r-15 bg-white mb-3">
                                <i class="ph-bold  ph-clock-countdown f-s-25 text-info"></i>
                            </div>
                            <p class="f-s-16">Total Siswa Melanggar Hari Ini</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="text-info-dark">{{ $totalSiswaMelanggar }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="ms-3 mt-3">Kelas IV</h5>

    <div class="row mb-5">
        <div class="col-12">
            <div class="content-wrapper mt-3">
                <div class="tabs-content active" id="tab-1">
                    <div class="mail-table">
                        @foreach ($this->students as $student)
                            <div class="mail-box">
                                <input class="form-check-input" id="inlineCheckbox1" type="checkbox" value="option1">

                                <div class="flex-grow-1 position-relative">
                                    <div class="mg-s-45 d-flex gap-2">
                                        <div class="pr-3" style="width: 60px; height: 60px;">
                                            <img style="width: 50px; height: 50px; object-fit:cover; border-radius: 100%"
                                                alt="" class="img-fluid"
                                                src="{{ $student->user->avatarUrl() }}">
                                        </div>

                                        <div>
                                            <h6 class="mb-0 f-w-600">{{ $student->name ?? '-' }}</h6>
                                            <h6 class="mb-0 f-w-600">NIS : {{ $student->nis ?? '-' }}</h6>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="btn-group dropdown-icon-none">
                                        <button aria-expanded="false"
                                            class="btn border-0 icon-btn b-r-4 dropdown-toggle"
                                            data-bs-auto-close="true" data-bs-toggle="dropdown" type="button">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ti ti-archive"></i>
                                                    Archive
                                                </a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ti ti-trash"></i>
                                                    Delete
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="ti ti-mail-opened"></i>
                                                    Read
                                                    Mali </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
