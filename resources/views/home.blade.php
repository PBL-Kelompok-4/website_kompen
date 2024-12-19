@extends('layouts.template')

@section('content')
    <div class="card">
        @if (auth()->user()->level->kode_level == 'ADM')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jumlah total jam kompen mahasiswa</h3>
                    <div class="card-tools"></div>
                </div>

                <div class="row">
                    <div class="card-body">
                        {{-- Pie Chart --}}
                        <h5 class="card-title"></h5>
                        <div style="width: 100%; max-width: 400px; margin: auto;">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @php
                // Query to fetch data from the mahasiswa table
                $data = DB::table('mahasiswa')
                    ->select(
                        DB::raw('SUM(jam_alpha) as total_alpha'),
                        DB::raw('SUM(jam_kompen) as total_kompen'),
                        DB::raw('SUM(jam_kompen_selesai) as total_kompen_selesai'),
                    )
                    ->first();
            @endphp
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector("#pieChart"), {
                        type: 'pie',
                        data: {
                            labels: ['Alpha', 'Kompen', 'Kompen Selesai'],
                            datasets: [{
                                label: 'Jumlah total jam kompen mahasiswa ',
                                data: [
                                    {{ $data->total_alpha ?? 0 }},
                                    {{ $data->total_kompen ?? 0 }},
                                    {{ $data->total_kompen_selesai ?? 0 }}
                                ],
                                backgroundColor: [
                                    'rgb(235, 217, 179)',
                                    'rgb(106, 155, 178)',
                                    'rgb(0, 25, 61)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });
                });
            </script>

        @elseif(auth()->user()->level->kode_level == 'DSN' || auth()->user()->level->kode_level == 'TDK')
            {{-- KODE TAMPILAN HTML TARUH SINI BUL GAWE DOSEN AMBEK TENDIK --}}
        
            <div class="card-header">
                <h3 class="card-title">Dashboard Personel Akademik</h3>
                <div class="card-tools"></div>
            </div>

            <style>
                .reject {
                    background-color: #EAD8B1;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .bi-x-circle {
                    color: #000000;
                    font-size: 24px
                }

                .pending {
                    background-color: #6A9AB0;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .accept {
                    background-color: #3A6D8C;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .bi-check-circle {
                    color: #000000;
                    font-size: 24px
                }

                .bi-clock {
                    color: #000000;
                    font-size: 24px
                }

                .reject h3, .pending h3, .accept h3,
                {
                font-size: 48px;
                /* Ukuran lebih besar */
                font-weight: bold;
                /* Tebal */
                color: #ffffff;
                /* Warna putih untuk kontras */
                text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
                /* Efek bayangan */
                }

                .Reject h3, .Pending h3, .Accept h3,
                {
                font-size: 16px;
                /* Sedikit lebih besar */
                font-weight: 500;
                /* Tebal sedang */
                color: #f0f0f0;
                /* Warna abu terang */
                letter-spacing: 0.5px;
                /* Jarak antar huruf */
                }
            </style>

            <div class="card-body">
                <div class="row">
                    <!-- Status Reject -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="reject inner">
                                <h3>{{ $totalReject }}</h3>
                                <p>Status Ditolak</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
            
                    <!-- Status Pending -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="pending inner">
                                <h3>{{ $totalPending }}</h3>
                                <p>Status Tertunda</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>
            
                    <!-- Status Accept -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="accept inner">
                                <h3>{{ $totalAccept }}</h3>
                                <p>Status Diterima</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
                <!-- Bar Chart -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Grafik Status Kompen</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="barChart" style="max-height: 400px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const ctx = document.querySelector("#barChart").getContext("2d");
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Reject', 'Pending', 'Accept'],
                            datasets: [{
                                label: 'Jumlah Total Status Kompen',
                                data: [
                                    {{ $totalReject }}, // Data dari controller untuk status reject
                                    {{ $totalPending }}, // Data dari controller untuk status pending
                                    {{ $totalAccept }}  // Data dari controller untuk status accept
                                ],
                                backgroundColor: [
                                    'rgb(235, 217, 179)', // Warna untuk reject
                                    'rgb(106, 155, 178)', // Warna untuk pending
                                    'rgb(58, 109, 140)'      // Warna untuk accept
                                ],
                                borderColor: [
                                    'rgba(0, 0, 0, 0.1)',
                                    'rgba(0, 0, 0, 0.1)',
                                    'rgba(0, 0, 0, 0.1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    enabled: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Status'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>

        @elseif(auth()->user()->level->kode_level == 'MHS')
            {{-- IKI GAWE MAHASISWA DEPEK NDEK IF KENE, LAK JUPUK DATA TEKOK CONTROLLER AE OJO TEKOK BLADE E --}}

            @php
                $id_mahasiswa = auth()->user()->id_mahasiswa;
            @endphp

            <div class="card-header">
                <h3 class="card-title">Dashboard Mahasiswa</h3>
                <div class="card-tools"></div>
            </div>

            <style>
                .Alpha {
                    background-color: #3A6D8C;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .bi-clock {
                    color: #000000;
                    font-size: 24px
                }

                .Kompen {
                    background-color: #6A9AB0;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .Selesai {
                    background-color: #EAD8B1;
                    padding: 10%;
                    margin: auto;
                    align-items: center;
                    justify-content: space-between;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(55, 109, 141, 0.1);
                    font-size:
                }

                .bi-check-circle {
                    color: #000000;
                    font-size: 24px
                }

                .Alpha h3, .Kompen h3, .Selesai h3,
                {
                font-size: 48px;
                /* Ukuran lebih besar */
                font-weight: bold;
                /* Tebal */
                color: #ffffff;
                /* Warna putih untuk kontras */
                text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
                /* Efek bayangan */
                }

                .Alpha p, .Kompen p, .Selesai p,
                {
                font-size: 16px;
                /* Sedikit lebih besar */
                font-weight: 500;
                /* Tebal sedang */
                color: #f0f0f0;
                /* Warna abu terang */
                letter-spacing: 0.5px;
                /* Jarak antar huruf */
                }
            </style>

            <div class="card-body">
                <div class="row">
                    <!-- Jam Alpha -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="Alpha inner">
                                <h3>{{ $mahasiswa->jam_alpha ?? 0 }}</h3>
                                <p>Jam Alpha</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Kompen -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="Kompen inner">
                                <h3>{{ $mahasiswa->jam_kompen ?? 0 }}</h3>
                                <p>Jam Kompen</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Kompen Selesai -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="Selesai inner">
                                <h3>{{ $mahasiswa->jam_kompen_selesai ?? 0 }}</h3>
                                <p>Jam Kompen Selesai</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
