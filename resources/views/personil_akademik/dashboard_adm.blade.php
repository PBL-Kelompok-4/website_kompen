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
        @endif

    </div>
@endsection
