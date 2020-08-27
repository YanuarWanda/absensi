<?php
    function getPertemuan(int $id) {
        $q = "SELECT * FROM pertemuan WHERE id_pertemuan = $id";
        $conn = getKoneksi();
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data pertemuan.");
            pindahHalaman("/guru/pertemuan/index.php?k=" . $id);
        }

        return $hasil->fetch_assoc();
    }
    function getSemuaAbsensi(int $idPertemuan) {
        $q = "SELECT p.id_pertemuan, km.id_pengguna, pn.nama, a.status, a.valid FROM kelas_murid km 
        JOIN pertemuan p USING (id_kelas) 
        LEFT JOIN absensi a USING (id_pertemuan, id_pengguna) 
        JOIN pengguna pn USING (id_pengguna) 
        WHERE id_pertemuan = $idPertemuan";
        $q_jumlah = "SELECT COUNT(*) AS jumlah FROM kelas_murid km 
        JOIN pertemuan p USING (id_kelas) 
        LEFT JOIN absensi a USING (id_pertemuan, id_pengguna) 
        JOIN pengguna pn USING (id_pengguna) 
        WHERE id_pertemuan = $idPertemuan";

        if (isset($_GET['cari'])) {
            $q = $q . " AND pn.nama LIKE '%" . $_GET['cari'] . "%'";
            $q_jumlah = $q_jumlah . " AND pn.nama LIKE '%" . $_GET['cari'] . "%'";
        }

        $conn = getKoneksi();

        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error", "Error", "Gagal mengambil data absensi.", $conn);
            return array();
        }

        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data absensi.", $conn);
            return array();
        }

        $jumlah_data = $hasil_jumlah->fetch_assoc()['jumlah'];
        return array(
            "data" => $hasil->fetch_all(MYSQLI_ASSOC),
            "jumlah" => $jumlah_data
        );
    }
    function ubahAbsensi(int $idPertemuan, int $idPengguna, int $idKelas, String $status, String $valid) {
        $q = "SELECT * FROM absensi WHERE id_pertemuan = $idPertemuan AND id_pengguna = $idPengguna";
        $conn = getKoneksi();
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Data absensi gagal diubah.", $conn);
            return false;
        }

        if ($hasil->num_rows > 0) {
            $q = "UPDATE absensi SET status = '$status', valid = $valid WHERE id_pertemuan = $idPertemuan AND id_pengguna = $idPengguna";
            if (!$conn->query($q)) {
                buatPesan("error", "Error", "Data absensi gagal diubah.", $conn);
                return false;
            }

            buatPesan("success", "Berhasil", "Data absensi berhasil diubah.");
            return true;
        } else {
            $q = "INSERT INTO absensi VALUES($idPertemuan, $idPengguna, '$status', $valid)";
            if (!$conn->query($q)) {
                buatPesan("error", "Error", "Data absensi gagal diubah.", $conn);
                return false;
            }

            buatPesan("success", "Berhasil", "Data absensi berhasil diubah.");
            return true;
        }
    }
    function konfirmasiAbsensi(int $idPengguna, int $idPertemuan) {
        $q = "UPDATE absensi SET valid = 1 WHERE id_pengguna = $idPengguna AND id_pertemuan = $idPertemuan";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Data absensi gagal dikonfirmasi.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Data absensi berhasil dikonfirmasi");
        return true;
    }
    function getAbsensi(String $cari, int $halaman = 1, int $idPengguna, int $idKelas) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT p.id_pertemuan, km.id_pengguna, a.status, a.valid,
        p.nama, p.deskripsi, p.tanggal_mulai, p.tanggal_selesai FROM kelas_murid km 
        JOIN pertemuan p USING (id_kelas) 
        LEFT JOIN absensi a USING (id_pertemuan, id_pengguna) 
        WHERE km.id_pengguna = $idPengguna 
        AND id_kelas = $idKelas";
        $q_jumlah = "SELECT COUNT(*) AS jumlah FROM kelas_murid km JOIN pertemuan p USING (id_kelas)
        LEFT JOIN absensi a USING (id_pertemuan, id_pengguna)
        WHERE km.id_pengguna = $idPengguna AND id_kelas = $idKelas";

        if (isset($cari)) {
            $q = $q . " AND p.nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " AND p.nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5; 
        $q = $q . " ORDER BY id_pertemuan ASC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_pertemuan ASC";
        
        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error" , "Error", "Gagal mengambil data absensi.", $conn);
            return array("data" => array());
        }

        // Mengambil data absensi
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data absensi.", $conn);
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
            buatPesan("error", "Error", "Gagal mengambil data kelas.", $conn);
            return false;
        }

        return $hasil->fetch_assoc();
    }
?>