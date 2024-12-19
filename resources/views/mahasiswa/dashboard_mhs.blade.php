@extends('layouts.template')

@section('content')
    <div class="card">

        @php
            $id_mahasiswa = auth()->user()->id_mahasiswa;
        @endphp

        @if auth()->user()->level->kode_level == "MHS"
        
            <div class="card-header">
                <h3 class="card-title">Dashboard Mahasiswa</h3>
                <div class="card-tools"></div>
            </div>

            <style>
                .Alpha, .Kompen, .Selesai {
                    padding: 10%;
                    margin: auto;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 300px;
                    height: 150px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                .Alpha { background-color: #3A6D8C; }
                .Kompen { background-color: #6A9AB0; }
                .Selesai { background-color: #EAD8B1; }

                .Alpha h3, .Kompen h3, .Selesai h3 {
                    font-size: 48px;
                    font-weight: bold;
                    color: #ffffff;
                    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
                }

                .Alpha p, .Kompen p, .Selesai p {
                    font-size: 16px;
                    color: #f0f0f0;
                }

                .icon i {
                    font-size: 24px;
                    color: #ffffff;
                }

                .small-box {
                    margin: 10px;
                }
            </style>

            <div class="card-body">
                <div class="row">
                    <!-- Jam Alpha -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="small-box">
                            <div class="Alpha inner">
                                <h3>{{ $mahasiswa->jam_alpha ?? 0}}</h3>
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
                                <h3>{{ $mahasiswa->jam_kompen ?? 0}}</h3>
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
                                <h3>{{ $mahasiswa->jam_kompen_selesai ?? 0}}</h3>
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
