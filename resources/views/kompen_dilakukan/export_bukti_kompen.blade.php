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
                <td width="15%" class="text-center"><img src="./assets/polinema-bw.png" class="logo-image"></td>
                <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">JL, Soekarno-Hatta No.9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
                </td>
            </tr>
        </table>
        <h3 class="text-center"> <u>BERITA ACARA KOMPENSASI PRESENSI</u></h3>
        <table class="content">
            <tr>
                <td style="width: 30%">Nama Pemberi Tugas</td>
                <td style="width: 20%">: {{ $bukti_kompen->kompen->personilAkademik->nama }}</td>
                <td style="width: 30%"></td>
                <td style="width: 20%">                    
                    <div style="width: 10px; height: 10px;">
                        <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code">
                    </div>
                </td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{$bukti_kompen->kompen->personilAkademik->nomor_induk}}</td>
                <td></td>
                <td></td>
            </tr>

            <br><br>
            <tr>
                <h4><b>Memberikan tugas kompensasi kepada :</b></h4>
            </tr>

            <tr>
                <td>Nama Mahasiswa</td>
                <td>: {{$bukti_kompen->mahasiswa->nama}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: {{$bukti_kompen->mahasiswa->nomor_induk}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: ..........</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: .................</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>: {{$bukti_kompen->kompen->nama}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Jumlah Jam</td>
                <td>: {{$bukti_kompen->kompen->jam_kompen}}</td>
                <td></td>
                <td></td>
            </tr>
            <br><br><br><br><br><br>
            <tr>
                <td class="text-center">Mengetahui </td>
                <td></td>
                <td></td>
                <td>Malang, ...........-.........-........</td>
            </tr>
            <tr>
                <td class="text-center">Ka. Progam Studi</td>
                <td></td>
                <td></td>
                <td>Yang Memberikan tugas kompen</td>
            </tr>
            <br><br><br><br><br>
            <tr>
                <td class="text-center"><b><u>(Hendra Pradibta, SE., M.Sc.)</u></b></td>
                <td></td>
                <td></td>
                <td><b><u>({{ $bukti_kompen->kompen->personilAkademik->nama }})</u></b></td>
            </tr>
            <tr>
                <td class="text-center"> NIP . 1983052122006041003</td>
                <td></td>
                <td></td>
                <td>NIP. {{ $bukti_kompen->kompen->personilAkademik->nomor_induk }}</td>
            </tr>
            <br><br>
            <tr>
                <td>No : {{ $bukti_kompen->kompen->nomor_kompen }}</td>
            </tr>
            <tr>
                <td><b>NB : Form ini wajib disimpan untuk keperluan bebas tanggungan</b></td>
            </tr>
        </table>
    </body>
</html>