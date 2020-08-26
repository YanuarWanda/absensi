<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/pertemuan.php");

    checkLogin("guru");

    $conn = getKoneksi();

    $tanggalMulai = date('Y-m-d H:i:s', strtotime($_POST['tanggalPertemuan'] . ' ' . $_POST['waktuPertemuanMulai']));
    $tanggalSelesai = date('Y-m-d H:i:s', strtotime($_POST['tanggalPertemuan'] . ' ' . $_POST['waktuPertemuanSelesai']));

    $hasil = tambahPertemuan(
        $conn->escape_string($_POST['idKelas']),
        $conn->escape_string($_POST['nama']),
        $conn->escape_string($_POST['deskripsi']),
        $conn->escape_string($tanggalMulai),
        $conn->escape_string($tanggalSelesai)
    );

    $conn->close();
    $hasil ? pindahHalaman("/guru/pertemuan/index.php?k=" . $_POST['idKelas']) : pindahHalaman("/guru/pertemuan/tambah.php?k=" . $_POST['idKelas']);
?>