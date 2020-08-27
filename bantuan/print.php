<?php
    function getPertemuan(int $idPertemuan, int $idKelas) {
        $q = "SELECT p.nama AS namaPertemuan, k.nama AS namaKelas, p.tanggal_mulai, p.tanggal_selesai, 
        pn.nama AS namaGuru, s.nama AS namaSekolah
        FROM pertemuan p JOIN kelas k USING(id_kelas) 
        JOIN sekolah s USING(id_sekolah) JOIN pengguna pn USING(id_pengguna)
        WHERE id_pertemuan = $idPertemuan";

        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if (!$hasil) {
            buatPesan("error", "Error", "Gagal membuat laporan absensi.", $conn);
            pindahHalaman("/guru/absensi/index.php?i=" . $idPertemuan . "&k=" . $idKelas);
            return false;
        }

        return $hasil->fetch_assoc();
    }

    function getAbsensi(int $idPertemuan, int $idKelas) {
        $q = "SELECT p.id_pertemuan, km.id_pengguna, pn.nama, a.status, a.valid FROM kelas_murid km 
        JOIN pertemuan p USING (id_kelas) 
        LEFT JOIN absensi a USING (id_pertemuan, id_pengguna) 
        JOIN pengguna pn USING (id_pengguna) 
        WHERE id_pertemuan = $idPertemuan";

        $conn = getKoneksi();
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data absensi.", $conn);
            pindahHalaman("/guru/absensi/index.php?i=" . $idPertemuan . "&k=" . $idKelas);
            return false;
        }

        return $hasil->fetch_all(MYSQLI_ASSOC);
    }
?>