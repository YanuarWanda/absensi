<?php
    function tampilForm(String $action, array $data = array()) {
        if (empty($data)) {
            $data = array(
                "id_sekolah" => "", "nama" => "", "alamat" => "", "kontak" => "", "email" => "", "status" => "N"
            );
        }

        echo '
        <form id="form" method="POST" action="' . $action . '" novalidate>
            <input type="hidden" name="id" value="' . $data["id_sekolah"] . '">
            <div class="form-group">
                <label for="nama">Nama Sekolah*</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama sekolah" tabindex="-1" autocomplete="off" maxlength="50" value="' . $data["nama"] . '" required autofocus>
                <div id="nama-invalid" class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat Sekolah*</label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat sekolah" required>' . $data["alamat"] . '</textarea>
                <div id="alamat-invalid" class="invalid-feedback"></div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="kontak">No Telepon Sekolah*</label>
                    <input type="text" name="kontak" id="kontak" class="form-control" autocomplete="off" placeholder="No telepon sekolah" pattern="\d*" maxlength="13" value="' . $data["kontak"] . '" required>
                    <div id="kontak-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="email">Email Sekolah*</label>
                    <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email sekolah" maxlength="50" value="' . $data["email"] . '" required>
                    <div id="email-invalid" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status Sekolah*</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="status" id="statusNegeri" class="custom-control-input" value="N" ' . ($data["status"] == "N" ? 'checked' : '') . '>
                        <label for="statusNegeri" class="custom-control-label">Negeri</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="status" id="statusSwasta" class="custom-control-input" value="S" ' . ($data["status"] == "S" ? 'checked' : '') . '>
                        <label for="statusSwasta" class="custom-control-label">Swasta</label>
                    </div>
                </div>
            </div>
            <div class="btn-group btn-block">
                <button type="reset" class="btn btn-outline-primary">Reset</button>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
        ';
    }
    
    function getSemuaSekolah(String $cari, int $halaman = 1) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT * FROM sekolah";
        $q_jumlah = "SELECT count(id_sekolah) AS jumlah FROM sekolah";

        if(isset($cari)) {
            $q = $q . " WHERE nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_sekolah DESC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_sekolah DESC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data sekolah." . (DEV ? "<br>" . $conn->error : "")
            );

            return array ("data" => array());
        }

        // Mengambil data sekolah
        $hasil = $conn->query($q);
        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data sekolah." . (DEV ? "<br>" . $conn->error : "")
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

    function getSekolah(int $id) {
        $conn = getKoneksi();

        $q = "SELECT * FROM sekolah WHERE id_sekolah = '$id'";

        $hasil = $conn->query($q);

        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data sekolah." . (DEV ? "<br>" . $conn->error : "")
            );

            pindahHalaman("/admin");
        }

        if ($hasil->num_rows <= 0) {
            $_SESSION['pesan'] = array(
                "tipe" => "info", 
                "judul" => "Data Tidak Ditemukan",
                "isi" => "Data sekolah tidak ditemukan." . (DEV ? "<br>" . $conn->error : "")
            );

            pindahHalaman("/admin");
        }

        return $hasil->fetch_assoc(); 
    }

    function periksaEmail(String $email, $edit = false, int $id = 0) {
        $q = "SELECT id_sekolah, email FROM sekolah WHERE email = '$email'";

        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if (!$hasil) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Gagal',
                'isi' => 'Data sekolah gagal ditambahkan.' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }

        $valid = true;
        if ($edit) {
            $valid = $hasil->fetch_assoc()['id_sekolah'] != $id;
        } 

        if ($hasil->num_rows > 0 && $valid) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Gagal',
                'isi' => 'Email sekolah sudah terdaftar' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }
        
        return true;
    }

    function tambahSekolah(String $nama, String $alamat, String $noTelepon, String $email, String $status) {
        $q = "INSERT INTO sekolah (nama, alamat, kontak, email, status) 
        VALUES('$nama', '$alamat', '$noTelepon', '$email', '$status')";

        $conn = getKoneksi();

        if (!periksaEmail($email)) return false;

        if(!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Gagal',
                'isi' => 'Data sekolah gagal ditambahkan.' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }

        $_SESSION['pesan'] = array(
            'tipe' => 'success',
            'judul' => 'Berhasil',
            'isi' => 'Data sekolah berhasil ditambahkan.'
        );

        return true;
    }

    function ubahSekolah(String $id, String $nama, String $alamat, String $noTelepon, String $email, String $status) {
        $q = "UPDATE sekolah SET nama='$nama', alamat='$alamat', 
        kontak='$noTelepon', email='$email', status='$status' 
        WHERE id_sekolah='$id'";

        $conn = getKoneksi();

        if (!periksaEmail($email, true, $id)) return false;

        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Gagal',
                'isi' => 'Data sekolah gagal diubah' . (DEV ? "<br>" . $conn->error : '')
            );  

            return false;
        }

        $_SESSION['pesan'] = array(
            'tipe' => 'success',
            'judul' => 'Berhasil',
            'isi' => 'Data sekolah berhasil diubah'
        );  

        return true;
    }

    function hapusSekolah(String $id) {
        $q = "DELETE FROM sekolah WHERE id_sekolah = '$id'";

        $conn = getKoneksi();

        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Error',
                'isi' => 'Gagal menghapus data sekolah.' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }

        $_SESSION['pesan'] = array(
            'tipe' => 'success',
            'judul' => 'Berhasil',
            'isi' => 'Berhasil menghapus data sekolah.'
        );

        return true;
    }
?>



