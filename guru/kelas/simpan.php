<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/kelas.php");

    checkLogin("guru");

    $conn = getKoneksi();

    $hasil = tambahKelas(
        $conn->escape_string($_SESSION['user']['id_pengguna']),
        $conn->escape_string($_SESSION['user']['id_sekolah']),
        $conn->escape_string($_POST['nama']),
        $conn->escape_string($_POST['deskripsi']),
        $conn->escape_string($_POST['kelasMulai']),
        $conn->escape_string($_POST['kelasSelesai'])
    );

    $conn->close();
    $hasil ? pindahHalaman("/guru/kelas") : pindahHalaman("/guru/kelas/tambah.php");
?>