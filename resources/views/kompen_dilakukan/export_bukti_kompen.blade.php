<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            body{
                font-family: "Times New Roman", Times, serif;
                /* margin: 6px 20px 5px 20px; */
                line-height: 15px;
            }
            table{
                width: 100%;
                border-collapse: collapse;
            }
            td, th{
                padding: 1px 1px;
            }
            th{
                text-align: left;
            }
            .d-block{
                display: block;
            }
            img.image{
                width: auto;
                height: 80px;
                max-width: 150px;
                max-height: 150px;
            }
            .text-right{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .p-1{
                padding: 5px 1px 5px 1px;
            }
            .font-10{
                font-size: 10pt;
            }
            .font-11{
                font-size: 11pt;
            }
            .font-12{
                font-size: 12pt;
            }
            .font-13{
                font-size: 13pt;
            }
            .border-bottom-header{
                border-bottom: 1px solid;
            }
            .border-all, .border-all th, .border-all td{
                border: 1px solid;
            }
            .logo-image{
                max-width: 100px; 
                max-height: 100px;
                width: auto;
                height: auto;
                object-fit: contain;
            }
            .bagian-bawah{
                display: flex;
            }
        </style>
    </head>
    <body>
        <table class="border-bottom-header">
            <tr>
                <td width="15%" class="text-center"><img src="{{ asset('assets/polinema-bw.png') }}" class="logo-image"></td>
                <td width="85%">
                    <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                    <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                    <span class="text-center d-block font-10">JL, Soekarno-Hatta No.9 Malang 65141</span>
                    <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax.(0341)404420, <a href="https://www.poltek-malang.ac.id/" target="_blank" rel="noopener noreferrer">https://www.poltek-malang.ac.id</a></span>
                </td>
            </tr>
        </table>

        <h3 class="text-center"> <u>BERITA ACARA KOMPENSASI PRESENSI</u></h3>

        <br>
        <p>Nama Pengajar : {{ $bukti_kompen->kompen->personilAkademik->nama }} </p>
        <p>NIP : {{ $bukti_kompen->kompen->personilAkademik->nomor_induk }}</p>
        <br>
        <p><b>Memberikan tugas kompensasi kepada :</b></p>
        <p>Nama Mahasiswa : {{ $bukti_kompen->mahasiswa->nama }} </p>
        <p>NIM : {{ $bukti_kompen->mahasiswa->nomor_induk }} </p>
        <p>Kelas : .......... </p>
        <p>Semester : .......................</p>
        <p>Pekerjaan : {{ $bukti_kompen->kompen->nama }} </p>
        <p>Jumlah Jam : {{ $bukti_kompen->kompen->jam_kompen }} </p>
        {{-- <img src="data:image/png;base64,{{ $qr_code }}" alt="QR CODE"> --}}
        <div class="container-bawah" style="border: 2px solid red">
            <div class="ttd-kajur bagian-bawah" id="ttd-kajur" >
            </div>
            <div class="div-qr bagian-bawah" id="div-qr">
                <div style="width: 200px; height: 200px;">
                    <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code">
                </div>
            </div>
            <div class="ttd-pemberi-tugas bagian-bawah" id="ttd-pemberi-tugas"></div>
        </div>
    </body>
</html>