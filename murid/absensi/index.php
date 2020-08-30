<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/absensi.php");
    
    checkLogin('murid');

    $cari = isset($_GET['cari']) ? $_GET['cari'] : "";
    $halaman = isset($_GET['p']) ? $_GET['p'] : 1;
    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;

    $arr = getAbsensi($cari, $halaman, $_SESSION['user']['id_pengguna'], $idKelas);
    $kelas = getKelas($idKelas);
?>

<!doctype html>
<html lang="en">
    <head>
        <?php headerHTML("Daftar Absensi | Absensi"); ?>
    </head>
    <body>
        <?php tampilHeader(); ?>

        <main role="main" class="ml-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom resp">
                <a href="../" class="btn btn-outline-secondary">
                    <span data-feather="arrow-left"></span>
                </a>
                <h1 class="h5 w-50 title">
                    <?php echo $kelas['nama']; ?>
                    <br><small class="text-muted"><?php echo $kelas['deskripsi']; ?></small>
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method="GET" action="<?php echo BASE_URL . '/murid/absensi'; ?>" class="btn-group mr-2">
                        <input type="text" name="cari" id="cari" class="form-control" placeholder="Nama pertemuan" value="<?php echo $cari; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <span data-feather="search"></span>
                        </button>
                    </form>
                </div>
            </div>

            <?php if(count($arr['data']) > 0 ) { ?>
                <p><?php echo empty($cari) ? "" : "Terdapat " . $arr['jumlah'] . " pertemuan dengan nama \"" . $cari . "\""; ?></p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" width="">No</th>
                                <th scope="col" width="">Pertemuan</th>
                                <th scope="col" width="">Tanggal</th>
                                <th scope="col" width="">Keterangan</th>
                                <th scope="col" width="">Validitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arr['data'] as $i => $absensi) { ?>
                                <tr>
                                    <td class="<?php echo $absensi['status'] != NULL ? getBgKeterangan($absensi['status']) : ""; ?>"><?php echo ($i + 1); ?></td>
                                    <td>
                                        <p class="m-0 p-0"><?php echo $absensi['nama']; ?></p>
                                        <small class="text-muted"><?php echo $absensi['deskripsi']; ?></small>
                                    </td>
                                    <td>
                                        <p class="m-0 p-0"><?php echo formatTanggal($absensi['tanggal_mulai']); ?></p>
                                        <small class="text-muted"><?php echo formatWaktu($absensi['tanggal_mulai']) . " - " . formatWaktu($absensi['tanggal_selesai']); ?></small>
                                    </td>
                                    <td>
                                        <form id="form<?php echo ($i + 1);?>" method="POST" action="ubahKeterangan.php">
                                            <input type="hidden" name="id_pengguna" value="<?php echo $absensi['id_pengguna']; ?>">
                                            <input type="hidden" name="id_pertemuan" value="<?php echo $absensi['id_pertemuan']; ?>">
                                            <input type="hidden" name="id_kelas" value="<?php echo $idKelas; ?>">
                                            <select name="status" class="custom-select keterangan" id="<?php echo ($i + 1); ?>" data-valid="<?php echo $absensi['valid']; ?>">
                                                <option value="H" <?php echo $absensi['status'] == 'H' ? 'selected' : ''; ?>>Hadir</option>
                                                <option value="S" <?php echo $absensi['status'] == 'S' ? 'selected' : ''; ?>>Sakit</option>
                                                <option value="I" <?php echo $absensi['status'] == 'I' ? 'selected' : ''; ?>>Izin</option>
                                                <option value="A" <?php echo $absensi['status'] == NULL || $absensi['status'] == 'A' ? 'selected' : ''; ?>>Alpa</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <?php if($absensi['valid']) { ?> 
                                            <span data-feather="check" class="text-success"></span>
                                        <?php } else if ($absensi['status'] != NULL) { ?>
                                            <span data-feather="x" class="text-danger"></span>
                                        <?php } else { ?>
                                            <span data-feather="alert-triangle" class="text-warning"></span>
                                        <?php } ?>
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
                                href="<?php echo BASE_URL . '/murid/absensi/index.php?cari=' . $cari . '&p=' . ($halaman - 1); ?>" 
                                tabindex="-1"
                            >
                                <span data-feather="arrow-left"></span>
                            </a>
                        </li>
                        <li class="page-item <?php echo $halaman * 5 >= $arr['jumlah'] ? 'disabled' : ''; ?>">
                            <a 
                                class="page-link" 
                                href="<?php echo BASE_URL . '/murid/absensi/index.php?cari=' . $cari . '&p=' . ($halaman + 1); ?>"
                            >
                                <span data-feather="arrow-right"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php } else {?>
                <p>Pertemuan dengan nama "<?php echo $cari; ?>" tidak ditemukan.</p>
            <?php } ?>
        </main>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>

        <script src="<?php echo BASE_URL . '/assets/js/absensi.js'; ?>"></script>
        <script>
            addEventKeterangan("Ubah keterangan absensi?", "keterangan", true);
        </script>
    </body>
</html>