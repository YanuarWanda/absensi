<?php
    function tampilForm(String $tujuan, array $data = array()) {
        if (empty($data)) {
            $data = array(
                "id_pengguna" => "", "id_sekolah" => "", "nama" => "", "alamat" => "", "kontak" => "", "email" => "", "tanggal_lahir" => "", "jenis_kelamin" => "L", "username" => "", "peran" => ""
            );
        }

        $arr = getSelectSekolah();
        $optionSekolah = "";

        foreach($arr as $sekolah) {
            $optionSekolah = $optionSekolah . '<option value="' . $sekolah["id_sekolah"] . '" ' . ($sekolah['id_sekolah'] == $data['id_sekolah'] ? "selected" : "") . '>' . $sekolah["nama"] . '</option>';
        }

        $inputSekolah = '
        <div class="form-group">
            <label for="sekolah">Sekolah*</label>
            <select class="custom-select" id="sekolah" name="sekolah" tabindex="-1" required autofocus>
                ' . $optionSekolah . '
            </select>
            <div id="sekolah-invalid" class="invalid-feedback"></div>
        </div>
        ';

        echo '
        <form id="form" method="POST" action="' . $tujuan . '" novalidate>
            <input type="hidden" name="idPengguna" value="' . $data["id_pengguna"] . '">
            ' . $inputSekolah . '
            <div class="row">
                <div class="form-group col">
                    <label for="nama">Nama Pengguna*</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama pengguna" autocomplete="off" maxlength="50" value="' . $data["nama"] . '" required>
                    <div id="nama-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="tanggalLahir">Tanggal Lahir*</label>
                    <input type="date" name="tanggalLahir" id="tanggalLahir" class="form-control" placeholder="Tanggal lahir" max="2015-01-01" value="' . $data["tanggal_lahir"] . '" required>
                    <div id="tanggalLahir-invalid" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Pengguna*</label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat pengguna" required>' . $data["alamat"] . '</textarea>
                <div id="alamat-invalid" class="invalid-feedback"></div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="kontak">No Telepon Pengguna*</label>
                    <input type="text" name="kontak" id="kontak" class="form-control" autocomplete="off" placeholder="No telepon pengguna" pattern="\d*" maxlength="13" value="' . $data["kontak"] . '" required>
                    <div id="kontak-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="email">Email Pengguna*</label>
                    <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email pengguna" maxlength="50" value="' . $data["email"] . '" required>
                    <div id="email-invalid" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="jenisKelamin">Jenis Kelamin*</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="jenisKelamin" id="l" class="custom-control-input" value="L" ' . ($data['jenis_kelamin'] == "L" ? "checked" : "") . '>
                        <label for="l" class="custom-control-label">Laki-laki</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="jenisKelamin" id="p" class="custom-control-input" value="P" ' . ($data['jenis_kelamin'] == "P" ? "checked" : "") . '>
                        <label for="p" class="custom-control-label">Perempuan</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="username">Username*</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" maxlength="50" value="' . $data["username"] . '" required>
                    <div id="username-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="peran">Peran*</label>
                    <select class="custom-select" id="peran" name="peran" required>
                        <option value="A" ' . ($data['peran'] == "A" ? "selected" : "") . '>Administrator</option>
                        <option value="G" ' . ($data['peran'] == "G" ? "selected" : "") . '>Guru</option>
                        <option value="M" ' . ($data['peran'] == "M" ? "selected" : "") . '>Murid</option>
                    </select>
                    <div id="peran-invalid" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="password">Password*</label>
                    <input type="password" name="password" id="password" class="form-control" maxlength="255" required>
                    <div id="password-invalid" class="invalid-feedback"></div>
                </div>
                <div class="form-group col">
                    <label for="konfirmasi">Konfirmasi Password*</label>
                    <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" maxlength="255" required>
                    <div id="konfirmasi-invalid" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="btn-group btn-block">
                <button type="reset" class="btn btn-outline-primary">Reset</button>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
        ';
    }

    function getSelectSekolah() {
        $q = "SELECT id_sekolah, nama FROM sekolah ORDER BY nama";

        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if(!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data sekolah." . (DEV ? "<br>" . $conn->error : "")
            );

            return array ();
        }

        return $hasil->fetch_all(MYSQLI_ASSOC);
    }

    function getSemuaPengguna(String $cari, int $halaman = 1) {
        $conn = getKoneksi();

        // Query
        $q = "SELECT p.id_pengguna, p.id_sekolah, s.nama AS namaSekolah, p.nama, p.kontak, p.email, tanggal_lahir, jenis_kelamin, peran FROM pengguna p JOIN sekolah s USING(id_sekolah)";
        $q_jumlah = "SELECT count(id_pengguna) AS jumlah FROM pengguna";

        if(isset($cari)) {
            $q = $q . " WHERE p.nama LIKE '%$cari%'";
            $q_jumlah = $q_jumlah . " WHERE nama LIKE '%$cari%'";
        }

        $mulai = ($halaman - 1) * 5;

        $q = $q . " ORDER BY id_pengguna DESC LIMIT $mulai,5";
        $q_jumlah = $q_jumlah . " ORDER BY id_pengguna DESC";

        // Periksa jumlah data
        $hasil_jumlah = $conn->query($q_jumlah);
        if (!$hasil_jumlah) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data pengguna." . (DEV ? "<br>" . $conn->error : "")
            );

            return array ("data" => array());
        }

        // Mengambil data pengguna
        $hasil = $conn->query($q);
        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error", 
                "judul" => "Error",
                "isi" => "Gagal mengambil data pengguna." . (DEV ? "<br>" . $conn->error : "")
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

    function periksaUsername(String $username, $edit = false, int $id = 0) {
        $q = "SELECT id_pengguna, username FROM pengguna WHERE username = '$username'";

        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data pengguna gagal ditambahkan." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        }

        $valid = true;
        if ($edit) {
            $valid = $hasil->fetch_assoc()['id_pengguna'] != $id;
        };

        if ($hasil->num_rows > 0 && $valid) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Gagal",
                "isi" => "Username pengguna sudah terdaftar." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        };

        return true;
    }

    function periksaEmail(String $email, $edit = false, int $id = 0) {
        $q = "SELECT id_pengguna, email FROM pengguna WHERE email = '$email'";

        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if (!$hasil) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Error',
                'isi' => 'Data pengguna gagal ditambahkan.' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }

        $valid = true;
        if ($edit) {
            $valid = $hasil->fetch_assoc()['id_pengguna'] != $id;
        } 

        if ($hasil->num_rows > 0 && $valid) {
            $_SESSION['pesan'] = array(
                'tipe' => 'error',
                'judul' => 'Gagal',
                'isi' => 'Email pengguna sudah terdaftar' . (DEV ? "<br>" . $conn->error : '')
            );

            return false;
        }
        
        return true;
    }

    function tambahPengguna(int $idSekolah, String $nama, String $alamat, String $kontak, String $email, String $tanggalLahir, String $jenisKelamin, String $username, String $password, String $peran) {
        $q = "INSERT INTO pengguna (id_sekolah, nama, alamat, kontak, 
        email, tanggal_lahir, jenis_kelamin, username, password, peran) 
        VALUES ('$idSekolah', '$nama', '$alamat', '$kontak', '$email', '$tanggalLahir', '$jenisKelamin', '$username', md5('$password'), '$peran')";

        $conn = getKoneksi();

        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Gagal menambahkan data pengguna." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        }

        if (!periksaEmail($email)) return false;
        if (!periksaUsername($username)) return false;

        $_SESSION['pesan'] = array(
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Berhasil menambahkan data pengguna."
        );

        return true;
    }

    function hapusPengguna(int $id) {
        $q = "DELETE FROM pengguna WHERE id_pengguna = '$id'";
        $conn = getKoneksi();
        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data pengguna gagal dihapus." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        };

        $_SESSION['pesan'] = array(
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Data pengguna berhasil dihapus."
        );

        return true;
    }

    function getPengguna(int $id) {
        $q = "SELECT * FROM pengguna WHERE id_pengguna = '$id'";
        
        $conn = getKoneksi();
        $hasil = $conn->query($q);

        if (!$hasil) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Gagal mengambil data pengguna." . (DEV ? "<br>" . $conn->error : "")
            );

            pindahHalaman("/admin/pengguna");
        }

        if ($hasil->num_rows <= 0) {
            $_SESSION['pesan'] = array(
                "tipe" => "info",
                "judul" => "Error",
                "isi" => "Data pengguna tidak ditemukan." . (DEV ? "<br>" . $conn->error : "")
            );

            pindahHalaman("/admin/pengguna");
        }

        return $hasil->fetch_assoc();
    }

    function updatePengguna(int $id, int $idSekolah, String $nama, String $alamat, String $kontak, String $email, String $tanggalLahir, String $jenisKelamin, String $username, String $password, String $peran) {
        $q = "UPDATE pengguna SET id_sekolah = $idSekolah, nama = '$nama', alamat = '$alamat', kontak = '$kontak', email = '$email', tanggal_lahir = '$tanggalLahir', jenis_kelamin = '$jenisKelamin', username = '$username', peran = '$peran'";

        if (strlen($password) > 0) {
            $q = $q . ", password = md5('$password')";
        }

        $q = $q . " WHERE id_pengguna = $id";

        $conn = getKoneksi();
        
        if (!$conn->query($q)) {
            $_SESSION['pesan'] = array(
                "tipe" => "error",
                "judul" => "Error",
                "isi" => "Data pengguna gagal diubah." . (DEV ? "<br>" . $conn->error : "")
            );

            return false;
        }

        if (!periksaEmail($email, true, $id)) return false;
        if (!periksaUsername($username, true, $id)) return false;

        $_SESSION['pesan'] = array(
            "tipe" => "success",
            "judul" => "Berhasil",
            "isi" => "Data pengguna berhasil diubah."
        );

        return true;
    }
?>