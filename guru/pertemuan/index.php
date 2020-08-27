<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/pertemuan.php");
    
    checkLogin('guru');

    $cari = isset($_GET['cari']) ? $_GET['cari'] : "";
    $halaman = isset($_GET['p']) ? $_GET['p'] : 1;
    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;

    $arr = getSemuaPertemuan($cari, $halaman, $idKelas);
    $kelas = getKelas($idKelas);
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Daftar Pertemuan | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <main role="main" class="ml-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom resp">
                <a href="../kelas" class="btn btn-outline-secondary">
                    <span data-feather="arrow-left"></span>
                </a>
                <h1 class="h5 w-50 title">
                    <?php echo $kelas['nama']; ?><br>
                    <small class="text-muted"><?php echo $kelas['deskripsi']; ?></small>
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method="GET" action="<?php echo BASE_URL . '/guru/pertemuan/'; ?>" class="btn-group mr-2">
                        <input type="hidden" name="k" value="<?php echo $idKelas; ?>">
                        <input type="text" name="cari" id="cari" class="form-control" placeholder="Nama pertemuan" value="<?php echo $cari; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <span data-feather="search"></span>
                        </button>
                    </form>
                    <a href="tambah.php?k=<?php echo $idKelas; ?>" class="btn btn-sm btn-outline-secondary">
                        <span data-feather="plus"></span>
                    </a>
                </div>
            </div>

            <?php if(count($arr['data']) > 0 ) { ?>
                <p><?php echo empty($cari) ? "" : "Terdapat " . $arr['jumlah'] . " pertemuan dengan nama \"" . $cari . "\""; ?></p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">No</th>
                                <th scope="col" width="55%">Pertemuan</th>
                                <th scope="col" width="25%">Tanggal</th>
                                <th scope="col" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arr['data'] as $i => $pertemuan) { ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td>
                                        <p class="m-0 p-0"><?php echo $pertemuan['nama']; ?></p>
                                        <small class="text-muted"><?php echo persentaseKehadiran($pertemuan['jumlah_hadir'], $pertemuan['jumlah_murid']) . " hadir (" . $pertemuan['jumlah_hadir'] . "/" . $pertemuan['jumlah_murid'] . ")"; ?></small>
                                    </td>
                                    <td>
                                        <p class="m-0 p-0"><?php echo formatTanggal($pertemuan['tanggal_mulai']); ?></p>
                                        <small class="text-muted"><?php echo formatWaktu($pertemuan['tanggal_mulai']) . " - " . formatWaktu($pertemuan['tanggal_selesai']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="../absensi/index.php?i=<?php echo $pertemuan['id_pertemuan']; ?>&k=<?php echo $idKelas; ?>" class="btn btn-warning text-light">
                                                <span data-feather="eye"></span>
                                            </a>
                                            <a href="edit.php?i=<?php echo $pertemuan['id_pertemuan']; ?>&k=<?php echo $idKelas; ?>" class="btn btn-primary">
                                                <span data-feather="edit"></span>
                                            </a>
                                            <a href="hapus.php?i=<?php echo $pertemuan['id_pertemuan']; ?>&k=<?php echo $idKelas; ?>" class="btn btn-danger btn-hapus">
                                                <span data-feather="trash"></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <nav aria-label="Navigasi data pertemuan" class="d-flex align-items-center justify-content-between">
                    <p><?php echo "Halaman " . $halaman . " dari " . $arr['jumlah_halaman']; ?></p>
                    <ul class="pagination">
                        <li class="page-item <?php echo $halaman == 1 ? 'disabled' : ''; ?>">
                            <a 
                                class="page-link" 
                                href="<?php echo BASE_URL . '/guru/pertemuan?k=' . $idKelas . '&cari=' . $cari . '&p=' . ($halaman - 1); ?>" 
                                tabindex="-1"
                            >
                                <span data-feather="arrow-left"></span>
                            </a>
                        </li>
                        <li class="page-item <?php echo $halaman * 5 >= $arr['jumlah'] ? 'disabled' : ''; ?>">
                            <a 
                                class="page-link" 
                                href="<?php echo BASE_URL . '/guru/pertemuan?k=' . $idKelas . '&cari=' . $cari . '&p=' . ($halaman + 1); ?>"
                            >
                                <span data-feather="arrow-right"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php } else { ?>
                <p>Pertemuan dengan nama "<?php echo $cari; ?>" tidak ditemukan.</p>
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