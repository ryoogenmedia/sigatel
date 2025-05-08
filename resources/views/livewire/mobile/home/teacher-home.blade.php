<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body px-1">
                <div class="d-flex">
                    <div class="mx-auto">
                        <img class="text-center" style="width: 250px"
                            src="{{ asset('ryoogen/illustration/education-app.jpg') }}" alt="ilustrasi">
                    </div>
                </div>

                <h4 class="text-center my-2">Selamat Datang, {{ auth()->user()->teacher->name ?? '' }}</h4>

                @if (auth()->user()->teacher->duty_status)
                    <p class="badge bg-success py-2">Anda Sekarang Piket</p>
                @else
                    <p class="badge bg-light-danger py-2">Anda Sekarang Tidak Piket</p>
                @endif
            </div>
        </div>

        <div class="card p-3">
            <div class="d-flex">
                <div class="d-flex-center text-center">
                    <span class="text-light-danger h-45 w-45 d-flex-center b-r-15">
                        <i class="ph-duotone  ph-student f-s-30"></i>
                    </span>
                </div>

                <div class="align-self-center ms-3">
                    <h4>Siswa Melanggar</h4>
                    <h2>{{ $this->jmlSiswaMelanggar }}</h2>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="d-flex">
                <div class="d-flex-center text-center">
                    <span class="text-light-info h-45 w-45 d-flex-center b-r-15">
                        <i class="ph-duotone  ph-book f-s-30"></i>
                    </span>
                </div>

                <div class="align-self-center ms-3">
                    <h4>Tugas Kelas</h4>
                    <h2>{{ $this->jmlPenugasanKelas }}</h2>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <div class="d-flex">
                <div class="d-flex-center text-center">
                    <span class="text-light-dark h-45 w-45 d-flex-center b-r-15">
                        <i class="ph-duotone  ph-graduation-cap f-s-30"></i>
                    </span>
                </div>

                <div class="align-self-center ms-3">
                    <h4>Siswa Kelas Anda</h4>
                    <h2>{{ $this->jmlSiswaAnda }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
