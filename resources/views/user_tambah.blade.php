<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah User</title>
</head>
<body>
    <h1>Form Tambah Data User</h1>
    <form method="post" action="{{url('/user/tambah_simpan')}}">

        {{ csrf_field() }}

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Username">
        <br>
        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukkan nama">
        <br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Passowrd">
        <br>
        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan ID Level">
        <br><br>
        <input type="submit" name="btn btn-success" value="simpan">
    </form>
</body>
</html>