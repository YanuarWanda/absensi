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
        $q = "UPDATE absensi SET valid = 1 WHERE id_pengguna = $idPengguna, id_pertemuan = $idPertemuan";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Data absensi gagal dikonfirmasi.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Data absensi berhasil dikonfirmasi");
        return true;
    }
?>