<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/pengguna.php");

    checkLogin("admin");

    if (!isset($_GET['i'])) {
        $_SESSION['pesan'] = array(
            "tipe" => "info",
            "judul" => "Tidak Ditemukan",
            "isi" => "Data pengguna tidak ditemukan."
        );

        pindahHalaman("/admin/pengguna");
        return;
    }

    $conn = getKoneksi();
    hapusPengguna($conn->escape_string($_GET['i']));
    $conn->close();
    pindahHalaman("/admin/pengguna");
?>