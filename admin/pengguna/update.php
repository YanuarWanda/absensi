<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/pengguna.php");

    $conn = getKoneksi();

    $hasil = updatePengguna(
        $conn->escape_string($_POST['idPengguna']),
        $conn->escape_string($_POST['sekolah']),
        $conn->escape_string($_POST['nama']),
        $conn->escape_string($_POST['alamat']),
        $conn->escape_string($_POST['kontak']),
        $conn->escape_string($_POST['email']),
        $conn->escape_string($_POST['tanggalLahir']),
        $conn->escape_string($_POST['jenisKelamin']),
        $conn->escape_string($_POST['username']),
        $conn->escape_string($_POST['password']),
        $conn->escape_string($_POST['peran'])
    );

    $conn->close();
    
    if (!$hasil) {
        pindahHalaman('/admin/pengguna/edit.php?i=' . $_POST['idPengguna']);
    }

    pindahHalaman("/admin/pengguna");
?>