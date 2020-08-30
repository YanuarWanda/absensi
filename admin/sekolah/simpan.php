<?php
    require_once "../../bantuan/functions.php";
    require_once "../../bantuan/sekolah.php";
    
    checkLogin('admin');

    $conn = getKoneksi();

    $hasil = tambahSekolah(
        $conn->escape_string($_POST['nama']),
        $conn->escape_string($_POST['alamat']),
        $conn->escape_string($_POST['kontak']),
        $conn->escape_string($_POST['email']),
        $conn->escape_string($_POST['status'])
    );

    $conn->close();

    $hasil ? pindahHalaman('/admin/sekolah') : pindahHalaman('/admin/sekolah/tambah.php');
?>