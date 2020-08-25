<?php 
	require_once "bantuan/functions.php";

	$username = $_POST['username'];
	$password = $_POST['password'];

	$q_select_pengguna = "SELECT * FROM pengguna 
	WHERE username = '$username' 
	AND password = md5('$password')";
    
    $conn = getKoneksi();
    $hasil = $conn->query($q_select_pengguna);
    
	if ($hasil && $hasil->num_rows > 0) {
		$row = $hasil->fetch_assoc();

		$_SESSION['user'] = $row;

		switch($row['peran']) {
			case 'A' : 
				pindahHalaman("/admin");
				break;
			case 'G' : 
				pindahHalaman("/guru");
				break;
			case 'M' : 
				pindahHalaman("/murid");
				break;
		}
	} else {
        $_SESSION['pesan'] = array(
            "tipe" => "info", 
            "judul" => "Gagal Login",
            "isi" => "Username dan password tidak cocok"
        );

		pindahHalaman('/');
	}

	$conn->close();
?>