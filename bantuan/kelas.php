<?php
    function tampilForm(String $tujuan, array $data = array()) {
        if (empty($data)) {
            $data = array("id_kelas" => "", "nama" => "", "deskripsi" => "", "kelas_mulai" => "", "kelas_selesai" => "");
        };

        echo '
        <form action="' . $tujuan . '" method="POST" id="form" novalidate>
            <input type="hidden" name="idKelas" value="' . $data["id_kelas"] . '">
            <div class="form-group">
                <label for="nama">Nama Kelas*</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama kelas" tabindex="-1" maxlength="50" value="' . $data["nama"] . '" required autofocus>
                <div id="nama-invalid" class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Kelas (optional)</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Deskripsi kelas">' . $data["deskripsi"] . '</textarea>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="kelasMulai">Tanggal Kelas Dibuka*</label>
                    <input type="date" name="kelasMulai" id="kelasMulai" class="form-control" value="' . $data["kelas_mulai"] . '" required>
                    <div id="kelasMulai-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="kelasSelesai">Tanggal Kelas Ditutup*</label>
                    <input type="date" name="kelasSelesai" id="kelasSelesai" class="form-control" value="' . $data["kelas_selesai"] . '" required disabled>
                    <div id="kelasSelesai-invalid" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="btn-group btn-block">
                <button type="reset" class="btn btn-outline-primary" id="btn-reset">Reset</button>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
        ';
    }

    function getSemuaKelas(String $cari, int $halaman = 1) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT id_kelas, nama, kelas_mulai, kelas_selesai,
        (SELECT COUNT(*) FROM pertemuan WHERE id_kelas = kelas.id_kelas) as jumlah_pertemuan,
        (SELECT COUNT(*) FROM kelas_murid WHERE id_kelas = kelas.id_kelas) as jumlah_murid 
        FROM kelas";
        $q_jumlah = "SELECT count(id_kelas) AS jumlah FROM kelas";

        if(isset($cari)) {
            $q = $q . " WHERE nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " AND id_pengguna = " . $_SESSION["user"]["id_pengguna"] . " ORDER BY id_kelas DESC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " AND id_pengguna = " . $_SESSION["user"]["id_pengguna"] . " ORDER BY id_kelas DESC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data kelas." . (DEV ? "<br>" . $conn->error : "")
            );

            return array ("data" => array());
        }

        // Mengambil data pengguna
        $hasil = $conn->query($q);
        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data kelas." . (DEV ? "<br>" . $conn->error : "")
            );

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

    function tambahKelas(int $idPengguna, int $idSekolah, String $nama, String $deskripsi, String $kelasMulai, String $kelasSelesai) {
        $q = "INSERT INTO kelas (id_pengguna, id_sekolah, nama, deskripsi, kelas_mulai, kelas_selesai) 
        VALUES('$idPengguna', '$idSekolah', '$nama', '$deskripsi', '$kelasMulai', '$kelasSelesai')";

        $conn = getKoneksi();

        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data sekolah gagal ditambahkan." . (DEV ? '<br>' . $conn->error : "")
            );

            return false;
        }

        $_SESSION['pesan'] = array(
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Data sekolah berhasil ditambahkan"
        );

        return true;
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

    function updateKelas(int $idKelas, String $nama, String $deskripsi, String $kelasMulai, String $kelasSelesai) {
        $q = "UPDATE kelas SET nama = '$nama', deskripsi = '$deskripsi', 
        kelas_mulai = '$kelasMulai', kelas_selesai = '$kelasSelesai' WHERE id_kelas = $idKelas";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data kelas gagal diperbaharui." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        }

        $_SESSION['pesan'] = array(
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Data kelas berhasil diperbaharui."
        );

        return true;
    }

    function hapusKelas(int $id) {
        $q = "DELETE FROM kelas WHERE id_kelas = $id";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data kelas gagal dihapus." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        }

        $_SESSION['pesan'] = array (
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Data kelas berhasil dihapus."
        );

        return true;
    }
?>