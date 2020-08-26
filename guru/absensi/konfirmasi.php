<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/absensi.php");

    checkLogin("guru");

    $idPertemuan = isset($_GET['i']) ? $_GET['i'] : 0;
    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;
    $idPengguna = isset($_GET['pn']) ? $_GET['pn'] : 0;

    $conn = getKoneksi();
    $hasil = konfirmasiAbsensi(
        $conn->escape_string($idPengguna),
        $conn->escape_string($idPertemuan)
    );
    $conn->close();
    pindahHalaman("/guru/absensi/index.php?i=" . $idPertemuan . "&k=" . $idKelas);
?>