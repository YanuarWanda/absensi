<?php
    function tampilForm(String $tujuan, array $data = array(), String $kelas_mulai = "", String $kelas_selesai = "") {
        if (empty($data)) {
            $data = array("id_pertemuan" => "", "id_kelas" => (isset($_GET['k']) ? $_GET['k'] : ""), "nama" => "", "deskripsi" => "", "tanggal_mulai" => null, "tanggal_selesai" => null);
        }

        echo '
        <form action="' . $tujuan . '" method="POST" id="form" novalidate>
            <input type="hidden" name="idPertemuan" value="' . $data["id_pertemuan"] . '">
            <input type="hidden" name="idKelas" value="' . $data["id_kelas"] . '">

            <div class="form-group">
                <label for="nama">Nama Pertemuan*</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama pertemuan" tabindex="-1" value="' . $data["nama"] . '" required autofocus>
                <div id="nama-invalid" class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="deskripsi">Alamat Pertemuan*</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi pertemuan">' . $data['deskripsi'] . '</textarea>
            </div>

            <div class="form-group">
                <label for="tanggalPertemuan">Tanggal Pertemuan*</label>
                <input type="date" name="tanggalPertemuan" id="tanggalPertemuan" class="form-control" min="' . $kelas_mulai . '" max="' . $kelas_selesai . '" value="' . (isset($data['tanggal_mulai']) ? formatInputTanggal($data['tanggal_mulai']) : "") . '" required>
                <div id="tanggalPertemuan-invalid" class="invalid-feedback"></div>
            </div>
            
            <div class="form-row">
                <div class="form-group col">
                    <label for="waktuPertemuanMulai">Waktu Pertemuan Mulai*</label>
                    <input type="time" name="waktuPertemuanMulai" id="waktuPertemuanMulai" class="form-control" value="' . (isset($data['tanggal_mulai']) ? formatWaktu($data['tanggal_mulai']) : "") . '" required>
                    <div id="waktuPertemuanMulai-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="waktuPertemuanSelesai">Waktu Pertemuan Mulai*</label>
                    <input type="time" name="waktuPertemuanSelesai" id="waktuPertemuanSelesai" class="form-control" value="' . (isset($data['tanggal_selesai']) ? formatWaktu($data['tanggal_selesai']) : "") . '" required disabled>
                    <div id="waktuPertemuanSelesai-invalid" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="btn-group btn-block">
                <button type="reset" class="btn btn-outline-primary" id="btn-reset">Reset</button>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
        ';
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

    function getSemuaPertemuan(String $cari, int $halaman = 1, $idKelas = 0) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT id_pertemuan, nama, tanggal_mulai, tanggal_selesai, 
        (SELECT COUNT(*) FROM absensi WHERE id_pertemuan = pertemuan.id_pertemuan AND status = 'H') AS jumlah_hadir, 
        (SELECT COUNT(*) FROM kelas_murid WHERE id_kelas = $idKelas) AS jumlah_murid 
        FROM pertemuan WHERE id_kelas = $idKelas";
        $q_jumlah = "SELECT COUNT(*) as jumlah FROM pertemuan WHERE id_kelas = $idKelas";

        if(isset($cari)) {
            $q = $q . " AND nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " AND nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_pertemuan ASC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_pertemuan ASC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            buatPesan("error", "Error", "Gagal mengambil data pertemuan.", $conn);
            return array ("data" => array());
        }

        // Mengambil data pengguna
        $hasil = $conn->query($q);
        if (!$hasil) {
            buatPesan("error", "Error", "Gagal mengambil data pertemuan.", $conn);
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

    function tambahPertemuan(int $idKelas, String $nama, String $deskripsi, String $tanggalMulai, String $tanggalSelesai) {
        $q = "INSERT INTO pertemuan (id_kelas, nama, deskripsi, tanggal_mulai, tanggal_selesai)
        VALUES ($idKelas, '$nama', '$deskripsi', '$tanggalMulai', '$tanggalSelesai')";

        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Data pertemuan gagal ditambahkan.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Data pertemuan berhasil ditambahkan.");
        return true;
    }

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

    function updatePertemuan(int $idPertemuan, int $idKelas, String $nama, String $deskripsi, String $tanggalMulai, String $tanggalSelesai) {
        $q = "UPDATE pertemuan SET nama = '$nama', deskripsi = '$deskripsi', 
        tanggal_mulai = '$tanggalMulai', tanggal_selesai = '$tanggalSelesai'
        WHERE id_pertemuan = $idPertemuan";

        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Data pertemuan gagal diperbaharui.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Data pertemuan berhasil diperbaharui.");
        return true;
    }

    function hapusPertemuan(int $id) {
        $q = "DELETE FROM pertemuan WHERE id_pertemuan = $id";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            buatPesan("error", "Error", "Data pertemuan gagal dihapus.", $conn);
            return false;
        }

        buatPesan("success", "Berhasil", "Data pertemuan berhasil dihapus.");
        return true;
    }
?>