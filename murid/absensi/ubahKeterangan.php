<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/absensi.php");

    checkLogin("murid");

    $conn = getKoneksi();
    $hasil = ubahAbsensi(
        $conn->escape_string($_POST['id_pertemuan']),
        $conn->escape_string($_POST['id_pengguna']),
        $conn->escape_string($_POST['id_kelas']),
        $conn->escape_string($_POST['status']),
        0
    );
    $conn->close();
    pindahHalaman("/murid/absensi/index.php?k=" . $_POST['id_kelas']);
?>