<?php
    require_once("../bantuan/functions.php");
    require_once("../bantuan/murid.php");

    checkLogin("murid");

    $conn = getKoneksi();

    if (!isset($_GET['i'])) {
        buatPesan("error", "Error", "Gagal keluar dari kelas.", $conn);
        return false;
    }

    keluarKelas($_GET['i'], $_SESSION['user']['id_pengguna']);
    $conn->close();
    pindahHalaman("/murid");
?>