@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dashboard Admin</h3>
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

            .Belum {
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

            .bi-x-square {
                color: #ffffff;
                font-size: 24px
            }

            .Alpha h3, .Kompen h3, .Selesai h3, .Belum h3 {
              font-size: 48px; /* Ukuran lebih besar */
              font-weight: bold; /* Tebal */
              color: #ffffff; /* Warna putih untuk kontras */
              text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6); /* Efek bayangan */
          }
          
          .Alpha p, .Kompen p, .Selesai p, .Belum p {
              font-size: 16px; /* Sedikit lebih besar */
              font-weight: 500; /* Tebal sedang */
              color: #f0f0f0; /* Warna abu terang */
              letter-spacing: 0.5px; /* Jarak antar huruf */
          }
          
            
        </style>

        <div class="row">
          <div class="col-lg-3 col-6" bis_skin_checked="1">
            <!-- small box -->
            <div class="small-box bg" bis_skin_checked="1">
              <div class="Alpha inner" bis_skin_checked="1">
                <h3>20</h3>

                <p>Jam Alpha</p>
              </div>
              <div class="icon" bis_skin_checked="1">
                <i class="bi bi-clock"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-6" bis_skin_checked="1">
            <!-- small box -->
            <div class="small-box bg" bis_skin_checked="1">
              <div class="Kompen inner" bis_skin_checked="1">
                <h3>15</h3>

                <p>Jam Kompen</p>
              </div>
              <div class="icon" bis_skin_checked="1">
                <i class="bi bi-clock"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-6" bis_skin_checked="1">
            <!-- small box -->
            <div class="small-box bg" bis_skin_checked="1">
              <div class="Selesai inner" bis_skin_checked="1">
                <h3>10</h3>

                <p>Jam Kompen Selesai</p>
              </div>
              <div class="icon" bis_skin_checked="1">
                <i class="bi bi-check-circle"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-6" bis_skin_checked="1">
            <!-- small box -->
            <div class="small-box bg" bis_skin_checked="1">
              <div class="Belum inner" bis_skin_checked="1">
                <h3>5</h3>

                <p>Jam Kompen Belum <br> Selesai</p>
              </div>
              <div class="icon" bis_skin_checked="1">
                <i class="bi bi-x-square"></i>
              </div>
            </div>
          </div>
        </div>

        

    </div>
</div>
@endsection