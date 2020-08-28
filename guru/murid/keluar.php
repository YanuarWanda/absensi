<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/murid.php");

    checkLogin("guru");

    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;
    $idPengguna = isset($_GET['i']) ? $_GET['i'] : 0;

    if ($idKelas == 0 || $idPengguna == 0) {
        buatPesan("info", "Tidak Ditemukan", "Gagal mengeluarkan murid dari kelas, karena data murid tidak ditemukan.");
        pindahHalaman("/guru/kelas");
    }

    $conn = getKoneksi();
    $hasil = keluarkanMurid($conn->escape_string($idKelas), $conn->escape_string($idPengguna));
    $conn->close();
    pindahHalaman("/guru/murid/index.php?k=" . $idKelas);
?>