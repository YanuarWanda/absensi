<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/murid.php");

    checkLogin("murid");

    $conn = getKoneksi();

    if (!isset($_GET['i'])) {
        buatPesan("error", "Error", "Gagal masuk kelas.", $conn);
        pindahHalaman("/murid");
        return false;
    }

    masukKelas($_GET['i'], $_SESSION['user']['id_pengguna']);
    $conn->close();
    pindahHalaman("/murid/kelas/cari.php?i=" . $_GET['i']);
?>