<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/pertemuan.php");

    checkLogin("guru");

    if (!isset($_GET['k'])) {
        buatPesan("info", "Tidak Ditemukan", "Data pertemuan tidak ditemukan.");
        pindahHalaman("/guru/kelas");
        return;
    }

    if (!isset($_GET['i'])) {
        buatPesan("info", "Tidak Ditemukan", "Data pertemuan tidak ditemukan.");
        pindahHalaman("/guru/pertemuan/index.php?k=" . $_GET['k']);
        return;
    }

    $conn = getKoneksi();
    $hasil = hapusPertemuan($conn->escape_string($_GET['i']));
    $conn->close();
    pindahHalaman("/guru/pertemuan/index.php?k=" . $_GET['k']);
?>