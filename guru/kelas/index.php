<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/kelas.php");
    
    checkLogin('guru');

    $cari = "";
    $halaman = 1;

    if(isset($_GET['cari'])) {
        $cari = $_GET['cari'];
    }

    if(isset($_GET['p'])) {
        $halaman = (int)$_GET['p'];
    }

    $arr = getSemuaKelas($cari, $halaman);
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Halaman Utama | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <main role="main" class="ml-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Daftar Kelas Saya</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method="GET" action="<?php echo BASE_URL . '/guru/kelas'; ?>" class="btn-group mr-2">
                        <input type="text" name="cari" id="cari" class="form-control" placeholder="Nama kelas" value="<?php echo $cari; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <span data-feather="search"></span>
                        </button>
                    </form>
                    <a href="tambah.php" class="btn btn-sm btn-outline-secondary">
                        <span data-feather="plus"></span>
                    </a>
                </div>
            </div>

            <?php if(count($arr['data']) > 0 ) { ?>
            <p><?php echo empty($cari) ? "" : "Terdapat " . $arr['jumlah'] . " kelas dengan nama \"" . $cari . "\""; ?></p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="">Kelas</th>
                            <th scope="col" width="">Tanggal Dibuka</th>
                            <th scope="col" width="">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($arr['data'] as $kelas) { ?>
                            <tr>
                                <td>
                                    <p class="m-0 p-0"><?php echo $kelas['nama']; ?></p>
                                    <small class="text-muted"><?php echo $kelas['jumlah_pertemuan'] . ' Pertemuan • ' . $kelas['jumlah_murid'] . ' Murid'; ?></small>
                                </td>
                                <td><?php echo formatTanggal($kelas['kelas_mulai']) . " - " . formatTanggal($kelas['kelas_selesai']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="../pertemuan/index.php?k=<?php echo $kelas['id_kelas']; ?>" class="btn btn-warning text-light">
                                            <span data-feather="eye"></span>
                                        </a>
                                        <a href="../murid/index.php?k=<?php echo $kelas['id_kelas']; ?>" class="btn btn-info">
                                            <span data-feather="users"></span>
                                        </a>
                                        <a href="edit.php?i=<?php echo $kelas['id_kelas']; ?>" class="btn btn-primary">
                                            <span data-feather="edit"></span>
                                        </a>
                                        <a href="hapus.php?i=<?php echo $kelas['id_kelas']; ?>" class="btn btn-danger btn-hapus">
                                            <span data-feather="trash"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Navigasi data kelas" class="d-flex align-items-center justify-content-between">
                <p><?php echo "Halaman " . $halaman . " dari " . $arr['jumlah_halaman']; ?></p>
                <ul class="pagination">
                    <li class="page-item <?php echo $halaman == 1 ? 'disabled' : ''; ?>">
                        <a 
                            class="page-link" 
                            href="<?php echo BASE_URL . '/guru/kelas?cari=' . $cari . '&p=' . ($halaman - 1); ?>" 
                            tabindex="-1"
                        >
                            <span data-feather="arrow-left"></span>
                        </a>
                    </li>
                    <li class="page-item <?php echo $halaman * 5 >= $arr['jumlah'] ? 'disabled' : ''; ?>">
                        <a 
                            class="page-link" 
                            href="<?php echo BASE_URL . '/guru/kelas?cari=' . $cari . '&p=' . ($halaman + 1); ?>"
                        >
                            <span data-feather="arrow-right"></span>
                        </a>
                    </li>
                </ul>
            </nav>

            <?php } else { ?>
                <p>Kelas dengan nama "<?php echo $cari; ?>" tidak ditemukan.</p>
            <?php } ?>
        </main>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>

        <script src="<?php echo BASE_URL . '/assets/js/hapus.js'; ?>"></script>
        <script>
            addHapusBtns();
        </script>
    </body>
</html>