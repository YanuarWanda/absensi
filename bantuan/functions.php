<?php 
    define("DEV", true);
    define(
        "BASE_URL", 
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) 
        ? "https://" 
        : "http://" 
        . $_SERVER['SERVER_NAME'] . '/uts'
    );
    define(
        "FULL_URL", 
        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
        . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
    );

    session_start();

    function getKoneksi() {
        $conn = @new mysqli("localhost", "root", "", "absensi");
        
	    if ($conn->connect_error) {
            die("Koneksi gagal. " . (DEV ? "<br>" . $conn->connect_error : ""));
        }
        
        return $conn;
    }
    function pindahHalaman(String $halaman) {
        header('Location: ' . BASE_URL . $halaman);
    }

    // Tampilan
    function tampilHeader() {
        echo '
        <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="' . BASE_URL . '/admin">Absensi</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                <a class="nav-link" href="' . BASE_URL . '/logout.php">
                    <span data-feather="log-out"></span>
                    Keluar
                </a>
                </li>
            </ul>
        </nav>
        ';
    }
    //belumberes
    function tampilSidebar(String $peran) {
        $menu = "";

        switch($peran) {
            case 'admin':
                $menu = '
                <li class="nav-item">
                    <a class="nav-link ' . (strpos($_SERVER['REQUEST_URI'], "/admin/sekolah") !== false ? 'active' : '') . '" href="' . BASE_URL . '/admin/sekolah">
                    <span data-feather="home"></span>
                    Sekolah <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ' . (strpos($_SERVER['REQUEST_URI'], "/admin/pengguna") !== false ? 'active' : '') . '" href="' . BASE_URL . '/admin/pengguna">
                    <span data-feather="users"></span>
                    Pengguna
                    </a>
                </li>
                ';
                break;
        }

        echo '
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    ' . $menu . '
                </ul>
            </div>
        </nav>
        ';
    }

    // Pesan
    function pesan() {
        if (isset($_SESSION['pesan'])) {
            tampilPesan($_SESSION['pesan']);
        }
    }
    function getIcon(String $tipe) {
        $icon = "";
        switch($tipe) {
            case 'success': 
                $icon = '
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill text-success mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                ';
                break;
            case 'error':
                $icon = '
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill text-danger mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                </svg>';
                break;
            case 'info':
                $icon = '
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill text-warning mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>';
                break;
        }
        return $icon;
    }
    function tampilPesan(Array $arrPesan) {
        $icon = getIcon($arrPesan['tipe']);

        echo '
        <div class="modal fade" id="pesan" tabindex="-1" aria-labelledby="pesan" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                        ' . $icon . '
                        ' . $arrPesan['judul'] . '
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . $arrPesan['isi'] . '
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#pesan").modal("show");
        </script>
        ';

        // Hapus pesan dari session
        unset($_SESSION['pesan']);
    }
    function buatPesan(String $tipe, String $judul, String $pesan, $conn = null) {
        $_SESSION['pesan'] = array(
            "tipe" => $tipe,
            "judul" => $judul,
            "isi" => $pesan . (DEV ? "<br>" . $conn->error : "")
        );
    }

    // Periksa peran
    function checkSudahLogin() {
        if (isset($_SESSION['user'])) {
            $peran = $_SESSION['user']['peran'];

            switch($peran) {
                case 'A': 
                    pindahHalaman("/admin");
                    break;
                case 'G':
                    pindahHalaman("/guru");
                    break;
                case 'M':
                    pindahHalaman("/murid");
                    break;
            }
        }
    }
    function checkLogin(String $peran) {
        if (!isset($_SESSION['user'])) {
            pindahHalaman("/");
            return false;
        } else {
            switch($peran) {
                case 'admin':
                    if ($_SESSION['user']['peran'] != 'A')
                    pindahHalaman('/');
                    return false;
                case 'guru':
                    if ($_SESSION['user']['peran'] != 'G')
                    pindahHalaman('/');
                    return false;
                case 'murid':
                    if ($_SESSION['user']['peran'] != 'M')
                    pindahHalaman('/');
                    return false;
            }
        }
        
        return true;
    }

    // Admin
    function getStatus(String $status) {
        switch($status) {
            case 'N': return "Negeri";
            case 'S': return "Swasta";
        }
    }
    function getJK(String $jk) {
        switch($jk) {
            case 'L': return "Laki-laki";
            case 'P': return "Perempuan";
        }
    }
    function getPeran(String $peran) {
        switch($peran) {
            case 'A': return "Administrator";
            case 'G': return "Guru";
            case 'M': return "Murid";
        }
    }
    
    // Tanggal
    function namaBulan($bulan) {
		switch($bulan) {
			case 1 : return "Januari";
			case 2 : return "Februari";
			case 3 : return "Maret";
			case 4 : return "April";
			case 5 : return "Mei";
			case 6 : return "Juni";
			case 7 : return "Juli";
			case 8 : return "Agustus";
			case 9 : return "September";
			case 10 : return "Oktober";
			case 11 : return "November";
			case 12 : return "Desember";
		}
	}
	function formatTanggal($strTanggal) {
		$fullTanggal = strtotime($strTanggal);
		$tanggal = date('d', $fullTanggal);
		$bulan = namaBulan(date('m', $fullTanggal));
		$tahun = date('Y', $fullTanggal);

		return $tanggal . ' ' . $bulan . ', ' . $tahun;
	}
	function formatInputTanggal($strTanggal) {
		return date('Y-m-d', strtotime($strTanggal));
	}
	function formatWaktu($strTanggal) {
		return date('H:i', strtotime($strTanggal));
    }
    
    // Guru
    function persentaseKehadiran(int $jumlahHadir, int $jumlahMurid) {
        return (floor($jumlahHadir / $jumlahMurid) * 100) . "%";
    }
    function getKeterangan($status) {
        switch ($status) {
            case "H": return "Hadir";
            case "A": return "Alpa";
            case "I": return "Izin";
            case "S": return "Sakit";
            default: return "Alpa";
        }
    }
    function getBgKeterangan(String $status) {
        switch ($status) {
            case "H": return "bg-success text-light";
            case "A": return "";
            case "I": return "bg-warning text-dark";
            case "S": return "bg-danger text-light";
            case "": return "";
            default: return "";
        }
    }
?>