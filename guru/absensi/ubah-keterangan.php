<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/absensi.php");

    checkLogin("guru");

    $conn = getKoneksi();
    $hasil = ubahAbsensi(
        $conn->escape_string($_POST['id_pertemuan']),
        $conn->escape_string($_POST['id_pengguna']),
        $conn->escape_string($_POST['id_kelas']),
        $conn->escape_string($_POST['status']),
        1,
    );
    $conn->close();
    pindahHalaman("/guru/absensi/index.php?i=" . $_POST['id_pertemuan'] . "&k=" . $_POST['id_kelas']);
?>