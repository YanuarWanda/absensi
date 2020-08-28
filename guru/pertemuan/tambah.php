<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/pertemuan.php");
    
    checkLogin('guru');

    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;

    if($idKelas == 0) {
        pindahHalaman("/guru/kelas");
    }

    $kelas = getKelas($idKelas);
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Tambah Pertemuan | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <div class="container-fluid">
            <div class="row">
                <main role="main" class="m-auto px-md-4 w-75">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <a href="index.php?k=<?php echo $idKelas; ?>" class="btn btn-sm btn-outline-secondary">
                                <span data-feather="arrow-left"></span>
                            </a>
                        </div>
                        <h1 class="h2">Tambah Pertemuan</h1>
                    </div>

                    <?php tampilForm("simpan.php", array(), $kelas["kelas_mulai"], $kelas["kelas_selesai"]); ?>
                </main>
            </div>
        </div>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>

        <script src="<?php echo BASE_URL . '/assets/js/validasi.js'; ?>"></script>
        <script>
            validasi(document.getElementById('form'), "pertemuan");
            waktuSelesai(document.getElementById("waktuPertemuanMulai"), document.getElementById("waktuPertemuanSelesai"));
        </script>
    </body>
</html>