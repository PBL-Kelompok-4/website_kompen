@extends('layouts.template')

@section('content')
    <div class="card">

        @if (auth()->user()->level->kode_level == 'ADM')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jumlah total kompen mahasiswa</h3>
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

            {{--  @php
                // Query to fetch data from the mahasiswa table
                $data = DB::table('mahasiswa')
                    ->select(
                        DB::raw('SUM(jam_alpha) as total_alpha'),
                        DB::raw('SUM(jam_kompen) as total_kompen'),
                        DB::raw('SUM(jam_kompen_selesai) as total_kompen_selesai'),
                    )
                    ->first();
            @endphp  --}}

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector("#pieChart"), {
                        type: 'pie',
                        data: {
                            labels: ['Alpha', 'Kompen', 'Kompen Selesai'],
                            datasets: [{
                                label: 'Jumlah total jam mahasiswa kompen',
                                data: [ 125, 250, 80 
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
        @endif

        @if (auth()->user()->level->kode_level == 'MHS')
            <div class="card-header">
                <h3 class="card-title">Dashboard Mahasiswa</h3>
                <div class="card-tools"></div>
            </div>
            <div class="card-body">

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
                        color: #ffffff;
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
                        color: #ffffff;
                        font-size: 24px
                    }


                    .Alpha h3,
                    .Kompen h3,
                    .Selesai h3,
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

                    .Alpha p,
                    .Kompen p,
                    .Selesai p,
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
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg">
                                <div class="Alpha inner">
                                    <h3>{{ $mahasiswa->jam_alpha }}</h3>
                                    <p>Jam Alpha</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg">
                                <div class="Kompen inner">
                                    <h3>{{ $mahasiswa->jam_kompen }}</h3>
                                    <p>Jam Kompen</p>
                                </div>
                                <div class="icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg">
                                <div class="Selesai inner">
                                    <h3>{{ $mahasiswa->jam_kompen_selesai }}</h3>
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
  </div>
@endsection
