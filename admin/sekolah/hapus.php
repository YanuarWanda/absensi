<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/sekolah.php");
    
    checkLogin('admin');

    if (!isset($_GET['i'])) {
        $_SESSION['pesan'] = array(
            "tipe" => "info",
            "judul" => "Tidak Ditemukan",
            "isi" => "Data sekolah tidak ditemukan."
        );

        pindahHalaman("/admin/sekolah");
        return;
    }

    $conn = getKoneksi();
    hapusSekolah($conn->escape_string($_GET['i']));
    $conn->close();
    pindahHalaman('/admin/sekolah');
?>