<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/kelas.php");

    checkLogin("guru");

    if (!isset($_GET['i'])) {
        $_SESSION['pesan'] = array(
            "tipe" => "info",
            "judul" => "Tidak Ditemukan",
            "isi" => "Data kelas tidak ditemukan."
        );

        pindahHalaman("/guru/kelas");
        return;
    }

    $conn = getKoneksi();
    hapusKelas($conn->escape_string($_GET['i']));
    $conn->close();
    pindahHalaman("/guru/kelas");
?>