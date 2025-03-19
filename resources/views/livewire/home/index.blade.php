<div>
    <x-slot name="title">Beranda</x-slot>

    <x-slot name="pagePretitle">Ringkasan aplikasi anda berada disini.</x-slot>

    <x-slot name="pageTitle">Beranda</x-slot>

    <div class="row">
        <div class="col-12 col-lg-3">
            <x-form.select wire:model.lazy="period" name="period">
                <option value="daily">- pilih -</option>
                <option value="daily">10 Hari Terakhir</option>
                <option value="weekly">10 Minggu Terakhir</option>
                <option value="monthly">10 Bulan Terakhir</option>
                <option value="yearly">10 Tahun Terakhir</option>
            </x-form.select>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4 col-lg-3">
            <x-card.count-data title="Siswa" :period="$this->period" :total="$this->totalStudent" icon="graduation-cap"
                color="blue" />
            <x-card.count-data title="Guru" :period="$this->period" :total="$this->totalTeacher" icon="chalkboard-teacher"
                color="red" />
            <x-card.count-data title="Orang Tua Siswa" :period="$this->period" :total="$this->totalStudentParent" icon="users"
                color="green" />
        </div>

        <div class="col-12 col-md-8 col-lg-9">
            <div class="card h-100 mb-3 w-100">
                <h4 class="card-header text-center">Data Piket Siswa
                    @switch($this->period)
                        @case('daily')
                            10 Hari Terkahir
                        @break

                        @case('weekly')
                            10 Minggu Terakhir
                        @break

                        @case('monthly')
                            10 Bulan Terakhir
                        @break

                        @case('yearly')
                            10 Tahun Terakhir
                        @break
                    @endswitch
                </h4>

                <div class="card-body py-2">
                    <div wire:ignore>
                        <div pending="{{ json_encode($this->onDutyPending['data']) }}"
                            approved="{{ json_encode($this->onDutyApproved['data']) }}"
                            reject="{{ json_encode($this->onDutyReject['data']) }}"
                            date="{{ json_encode($this->onDutyApproved['date']) }}" id="chart-mentions"
                            class="chart-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4" wire:poll.30000ms>
        <h4 class="card-header">Riwayat Login Pengguna</h4>
        <div class="card-body">
            <div class="row">
                @forelse ($login_history as $login)
                    <div class="col col-md-4 col-xl-3 mb-2">
                        <div>
                            <div class="d-flex">
                                <div class="mt-1 ms-1">
                                    <img style="width: 53px; height: 53px; object-fit:cover; border-radius: 10px"
                                        src="{{ $login->avatarUrl() }}" alt="avatar">
                                </div>

                                <div class="ms-2">
                                    <div class="header font-weight-bold">
                                        @if (isset($login->account_holder))
                                            <small><b>{{ $login->account_holder->name ?? '-' }}</b></small>
                                        @else
                                            <small><b>{{ $login->username ?? '-' }}</b></small>
                                        @endif

                                        @if (is_online($login->id))
                                            <span class="badge bg-success ms-1"></span>
                                        @else
                                            <small class="badge bg-secondary ms-1"
                                                title="{{ $login->last_seen_time }}"></small>
                                        @endif
                                    </div>

                                    <div class="subheader mb-1">
                                        {{ \Carbon\Carbon::parse($login->last_login_time)->diffForHumans() ?? '-' }}
                                    </div>

                                    <div class="subheader mb-1">
                                        <span @class([
                                            'badge',
                                            'bg-green-lt' => $login->roles == 'admin',
                                            'bg-blue-lt' => $login->roles == 'teacher',
                                            'bg-yellow-lt' => $login->roles == 'student',
                                            'bg-pink-lt' => $login->roles == 'parent',
                                        ])>{{ $login->roles }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="las la-clock" style="font-size:50px"></i>
                        </div>

                        <p class="empty-title">Data Riwayat Login Belum Ada.</p>

                        <p class="empty-subtitle text-muted">
                            Data riwayat login akan muncul dengan sendirinya, ketika akun pengguna sedang login.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let chart;
                const item = document.getElementById('chart-mentions');

                function renderChart(approved, pending, reject, date) {
                    if (!item) {
                        console.error("ELEMENT ID #chart-mentions TIDAK DITEMUKAN!");
                        return;
                    }

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new ApexCharts(item, {
                        chart: {
                            type: "bar",
                            stacked: true,
                            height: 340,
                            parentHeightOffset: 0,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true
                            }
                        },
                        stroke: {
                            show: true,
                            width: 1,
                            colors: ['#fff']
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        series: [{
                                name: "Piket Selesai",
                                data: approved
                            },
                            {
                                name: "Piket Pending",
                                data: pending
                            },
                            {
                                name: "Piket Batal",
                                data: reject
                            }
                        ],
                        xaxis: {
                            categories: date,
                            labels: {
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        colors: ["#4ade80", "#fc9f13", "#f11a1a"],
                        legend: {
                            show: true
                        }
                    });

                    chart.render();
                }

                Livewire.on('updateChart', (data) => {
                    let approved = data[0].approved;
                    let pending = data[0].pending;
                    let reject = data[0].reject;
                    let date = data[0].date;

                    renderChart(approved, pending, reject, date);
                });

                renderChart(
                    JSON.parse(item.getAttribute('approved')),
                    JSON.parse(item.getAttribute('pending')),
                    JSON.parse(item.getAttribute('reject')),
                    JSON.parse(item.getAttribute('date'))
                );
            });
        </script>
    @endpush
</div>
