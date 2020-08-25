<?php 
    require_once("../bantuan/functions.php");
    require_once("../bantuan/links.php"); 
    require_once("../bantuan/sekolah.php");
    
    checkLogin('admin');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Halaman Utama | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <div class="container-fluid">
            <div class="row">
                <?php tampilSidebar('admin'); ?>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Daftar Sekolah</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <form method="GET" action="<?php echo BASE_URL . '/admin'; ?>" class="btn-group mr-2">
                                <input type="text" name="cari" id="cari" class="form-control" placeholder="Nama sekolah" value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <span data-feather="search"></span>
                                </button>
                            </form>
                            <a href="kelas/tambah.php" class="btn btn-sm btn-outline-secondary">
                                <span data-feather="plus"></span>
                            </a>
                        </div>
                    </div>
                    <?php 
                        $cari = "";
                        $halaman = 1;
                        if(isset($_GET['cari'])) {
                            $cari = $_GET['cari'];
                        }
                        if(isset($_GET['p'])) {
                            $halaman = (int)$_GET['p'];
                        }
                        $arrSekolah = getSemuaSekolah($cari, $halaman);
                        if(count($arrSekolah['data']) > 0 ) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" width="65%">Sekolah</th>
                                    <th scope="col" width="17.5%">Kontak</th>
                                    <th scope="col" width="7.5%">Status</th>
                                    <th scope="col" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        foreach($arrSekolah['data'] as $sekolah) {
                                ?>
                                    <tr>
                                        <td>
                                            <p class="m-0 p-0"><?php echo $sekolah['nama']; ?></p>
                                            <small class="text-muted"><?php echo $sekolah['alamat']; ?></small>
                                        </td>
                                        <td>
                                            <p class="m-0 p-0"><?php echo $sekolah['kontak']; ?></p>
                                            <small class="text-muted"><?php echo $sekolah['email']; ?></small>
                                        </td>
                                        <td><?php echo getStatus($sekolah['status']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="kelas/ubah.php?i=<?php echo $sekolah['id_sekolah']; ?>" class="btn btn-primary">
                                                    <span data-feather="edit"></span>
                                                </a>
                                                <a href="#" class="btn btn-danger">
                                                    <span data-feather="trash"></span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                        }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Navigasi data sekolah" class="d-flex align-items-center justify-content-between">
                        <p><?php echo "Halaman " . $halaman . " dari " . $arrSekolah['jumlah_halaman']; ?></p>
                        <ul class="pagination">
                            <li class="page-item <?php echo $halaman == 1 ? 'disabled' : ''; ?>">
                                <a 
                                    class="page-link" 
                                    href="<?php echo BASE_URL . '/admin?cari=' . $cari . '&p=' . ($halaman - 1); ?>" 
                                    tabindex="-1"
                                >
                                    <span data-feather="arrow-left"></span>
                                </a>
                            </li>
                            <li class="page-item <?php echo $halaman * 5 >= $arrSekolah['jumlah'] ? 'disabled' : ''; ?>">
                                <a 
                                    class="page-link" 
                                    href="<?php echo BASE_URL . '/admin?cari=' . $cari . '&p=' . ($halaman + 1); ?>"
                                >
                                    <span data-feather="arrow-right"></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php
                        } else {
                    ?>
                        <p>Sekolah dengan nama '<?php echo $_GET['cari']; ?>' tidak ditemukan.</p>
                    <?php
                        }
                    ?>
                </main>
            </div>
        </div>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>
    </body>
</html>