<?php
    function getSemuaSekolah(String $cari, int $halaman = 1) {
        $conn = getKoneksi();

        $q = "SELECT * FROM sekolah";
        $q_jumlah = "SELECT count(id_sekolah) AS jumlah FROM sekolah";

        if(isset($cari)) {
            $q = $q . " WHERE nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_sekolah LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_sekolah";

        $hasil = $conn->query($q);
        $hasil_jumlah = $conn->query($q_jumlah);

        if($hasil && $hasil_jumlah) {
            $jumlah_data = $hasil_jumlah->fetch_assoc()['jumlah'];
            $jumlah_halaman = $jumlah_data % 5 > 0 ? floor($jumlah_data / 5) + 1 : floor($jumlah_data / 5);

            return array(
                "jumlah_halaman" => $jumlah_halaman,
                "jumlah" => $jumlah_data,
                "data" => $hasil->fetch_all(MYSQLI_ASSOC)
            );
        } else {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data sekolah." . DEV ? "<br>" . $hasil->error : ""
            );
        }
    }
?>