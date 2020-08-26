<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/kelas.php");

    checkLogin("guru");

    $conn = getKoneksi();

    $hasil = updateKelas(
        $conn->escape_string($_POST['idKelas']),
        $conn->escape_string($_POST['nama']),
        $conn->escape_string($_POST['deskripsi']),
        $conn->escape_string($_POST['kelasMulai']),
        $conn->escape_string($_POST['kelasSelesai'])
    );

    $conn->close();

    $hasil ? pindahHalaman("/guru/kelas") : pindahHalaman("/guru/kelas/edit.php?i=" . $_POST['idKelas']);
?>