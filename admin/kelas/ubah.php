<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/sekolah.php");
    
    checkLogin('admin');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Ubah Sekolah | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <div class="container-fluid">
            <div class="row">
                <?php tampilSidebar('admin'); ?>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <a href="../index.php" class="btn btn-sm btn-outline-secondary">
                                <span data-feather="arrow-left"></span>
                            </a>
                        </div>
                        <h1 class="h2">Ubah Sekolah</h1>
                    </div>
                </main>
            </div>
        </div>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>
    </body>
</html>