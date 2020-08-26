<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/kelas.php");
    
    checkLogin('guru');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Tambah Kelas | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <div class="container-fluid">
            <div class="row">
                <main role="main" class="m-auto px-md-4 w-75">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <a href="index.php" class="btn btn-sm btn-outline-secondary">
                                <span data-feather="arrow-left"></span>
                            </a>
                        </div>
                        <h1 class="h2">Tambah Sekolah</h1>
                    </div>

                    <?php tampilForm("simpan.php"); ?>
                </main>
            </div>
        </div>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>

        <script src="<?php echo BASE_URL . '/assets/js/validasi.js'; ?>"></script>
        <script>
            validasi(document.getElementById('form'), "kelas");
            tanggalSelesai(document.getElementById("kelasMulai"), document.getElementById("kelasSelesai"));
        </script>
    </body>
</html>