<?php 
    function getSemuaCariKelas(String $cari, int $halaman = 1, int $idPengguna, int $idSekolah) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT id_kelas, k.nama, kelas_mulai, kelas_selesai, p.nama AS namaGuru, deskripsi
        FROM kelas k JOIN pengguna p USING(id_pengguna)";
        $q_jumlah = "SELECT count(id_kelas) AS jumlah FROM kelas";

        if(isset($cari)) {
            $q = $q . " WHERE k.nama LIKE '%$cari%' AND k.id_sekolah = $idSekolah AND id_kelas NOT IN (SELECT id_kelas FROM kelas_murid WHERE id_kelas = k.id_kelas AND id_pengguna = $idPengguna)";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%' AND id_sekolah = $idSekolah AND id_kelas NOT IN (SELECT id_kelas FROM kelas_murid WHERE id_kelas = id_kelas AND id_pengguna = $idPengguna)";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_kelas DESC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_kelas DESC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error", "Error", "Gagal mengambil data kelas.", $conn);
            return array ("data" => array());
        }

        // Mengambil data pengguna
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data kelas.", $conn);
            return array ("data" => array());
        }

        $jumlah_data = $hasil_jumlah->fetch_assoc()['jumlah'];
        $jumlah_halaman = $jumlah_data % 5 > 0 ? floor($jumlah_data / 5) + 1 : floor($jumlah_data / 5);
        return array(
            "jumlah_halaman" => $jumlah_halaman,
            "jumlah" => $jumlah_data,
            "data" => $hasil->fetch_all(MYSQLI_ASSOC)
        );
    }
    function getSemuaKelas(String $cari, int $halaman = 1, int $idPengguna) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT id_kelas, k.nama, kelas_mulai, kelas_selesai, p.nama AS namaGuru, deskripsi
        FROM kelas k JOIN pengguna p USING(id_pengguna)";
        $q_jumlah = "SELECT count(id_kelas) AS jumlah FROM kelas";

        if(isset($cari)) {
            $q = $q . " WHERE k.nama LIKE '%$cari%' AND id_kelas IN (SELECT id_kelas FROM kelas_murid WHERE id_pengguna = $idPengguna)";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%' AND id_kelas IN (SELECT id_kelas FROM kelas_murid WHERE id_pengguna = $idPengguna)";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_kelas DESC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_kelas DESC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error", "Error", "Gagal mengambil data kelas.", $conn);
            return array ("data" => array());
        }

        // Mengambil data pengguna
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data kelas.", $conn);
            return array ("data" => array());
        }

        $jumlah_data = $hasil_jumlah->fetch_assoc()['jumlah'];
        $jumlah_halaman = $jumlah_data % 5 > 0 ? floor($jumlah_data / 5) + 1 : floor($jumlah_data / 5);
        return array(
            "jumlah_halaman" => $jumlah_halaman,
            "jumlah" => $jumlah_data,
            "data" => $hasil->fetch_all(MYSQLI_ASSOC)
        );
    }
    function keluarKelas(int $idKelas, int $idPengguna) {
        $q = "DELETE FROM kelas_murid WHERE id_kelas = $idKelas AND id_pengguna = $idPengguna";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Gagal keluar dari kelas.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Berhasil keluar dari kelas.");
        return true;
    }
    function masukKelas(int $idKelas, int $idPengguna) {
        $q = "INSERT INTO kelas_murid VALUES($idKelas, $idPengguna)";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Gagal masuk kelas.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Berhasil masuk kelas.");
        return true;
    }
    function getSemuaMuridByKelas(String $cari, int $halaman = 1, int $idKelas) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT id_pengguna, nama, 
        (SELECT COUNT(*) FROM absensi JOIN pertemuan USING (id_pertemuan) WHERE id_pengguna = kelas_murid.id_pengguna AND id_kelas = $idKelas AND status = 'H') AS jumlah_hadir, 
        (SELECT COUNT(*) FROM pertemuan WHERE id_kelas = $idKelas) AS jumlah_pertemuan 
        FROM kelas_murid JOIN pengguna USING (id_pengguna) WHERE id_kelas = $idKelas";
        $q_jumlah = "SELECT COUNT(*) AS jumlah FROM kelas_murid JOIN pengguna USING (id_pengguna) WHERE id_kelas = $idKelas";

        if ($cari) {
            $q = $q . " AND nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " AND nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;
        $q = $q . " ORDER BY id_pengguna DESC LIMIT $mulai, 5";
        $q_jumlah = $q_jumlah . " ORDER BY id_pengguna";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error", "Error", "Gagal mengambil data murid.", $conn);
            return array("data" => array());
        }

        // Mengambil data murid
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data murid.", $conn);
            return array("data" => array());
        }

        $jumlah_data = $hasil_jumlah->fetch_assoc()['jumlah'];
        $jumlah_halaman = $jumlah_data % 5 > 0 ? floor($jumlah_data / 5) + 1 : floor($jumlah_data / 5);
        return array(
            "jumlah_halaman" => $jumlah_halaman,
            "jumlah" => $jumlah_data,
            "data" => $hasil->fetch_all(MYSQLI_ASSOC)
        );        
    }
    function getKelas(int $id) {
        $q = "SELECT * FROM kelas WHERE id_kelas = $id";
        $conn = getKoneksi();
        $hasil = $conn->query($q);
        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Gagal mengambil data kelas." . (DEV ? "<br>" . $conn->error : "")
            );

            pindahHalaman("/guru/kelas");
        }

        if ($hasil->num_rows <= 0) {
            $_SESSION['pesan'] = array(
                "tipe" => "info",
                "judul" => "Tidak Ditemukan",
                "isi" => "Data kelas tidak ditemukan."
            );

            pindahHalaman("/guru/kelas");
        }

        return $hasil->fetch_assoc();
    }
    function keluarkanMurid(int $idKelas, int $idPengguna) {
        $conn = getKoneksi();

        $q = "DELETE FROM kelas_murid WHERE id_kelas = $idKelas AND id_pengguna = $idPengguna";
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Gagal mengeluarkan murid dari kelas.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Berhasil mengeluarkan murid dari kelas.");
        return true;
    }
?>