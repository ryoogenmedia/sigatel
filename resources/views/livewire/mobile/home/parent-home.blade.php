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

                <h5 class="text-center mt-2">Selamat Datang Kembali, {{ auth()->user()->parent->name ?? '' }}</h5>
                <p class="text-center mb-2">Anda berperan sebagai orang tua siswa
                    <b>{{ ucwords(auth()->user()->parent->student->name ?? '-') }}</b>
                </p>

                <div class="d-flex justify-content-center">
                    <a href="{{ route('mobile.biodata-student.index') }}" class="btn btn-outline-success btn-xs">Bio
                        Data Anak</a>
                </div>
            </div>
        </div>

        <x-form.select wire:model.live="filterTime" name="filterTime" label="Waktu Pelanggaran">
            <option value="">Semua Data</option>
            <option value="today">Hari Ini</option>
            <option value="this_week">Minggu Ini</option>
            <option value="this_month">Bulan Ini</option>
            <option value="this_year">Tahun Ini</option>
        </x-form.select>

        <div class="card p-3">
            <div class="d-flex">
                <div class="d-flex-center text-center">
                    <span class="text-light-danger h-45 w-45 d-flex-center b-r-15">
                        <i class="ph-duotone ph-x-circle f-s-30"></i>
                    </span>
                </div>

                <div class="align-self-center ms-3">
                    <h4>Pelanggaran Sekolah</h4>
                    <h2>{{ $this->jmlPelanggaran }}</h2>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header h-100 mb-3 w-100">
                <h5 class="text-center">Persentasi Pelanggaran</h5>
                <p class="text-center">Pelanggaran Dalam 365 Hari / 1 Tahun</p>
                <p class="text-center">(Jumlah Pelanggaran / 365 Hari) x 100</p>
            </div>
            <div class="card-body py-2">
                <div wire:ignore>
                    <div percent="{{ $this->violationsPercent }}" id="chart-mentions" class="chart-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let chart;
            const item = document.getElementById('chart-mentions');

            function renderChart(percent) {
                if (!item) {
                    console.error("ELEMENT ID #chart-mentions TIDAK DITEMUKAN!");
                    return;
                }

                if (chart) {
                    chart.destroy();
                }

                chart = new ApexCharts(item, {
                    chart: {
                        type: "radialBar",
                        height: 340,
                        animations: {
                            enabled: true
                        }
                    },
                    series: [percent],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: '60%'
                            },
                            dataLabels: {
                                show: true,
                                name: {
                                    show: false
                                },
                                value: {
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    formatter: function(val) {
                                        return val + "%";
                                    }
                                }
                            }
                        }
                    },
                    colors: ["#ff0047"]
                });

                chart.render();
            }

            Livewire.on('updatedPercent', (val) => {
                renderChart(parseFloat(val[0].percent));
            });

            renderChart(parseFloat(item.getAttribute('percent')));
        });
    </script>
@endpush
