<?php 
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/links.php"); 
    require_once("../../bantuan/absensi.php");
    
    checkLogin('guru');

    $idPertemuan = isset($_GET['i']) ? $_GET['i'] : 0;
    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;
    $cari = isset($_GET['cari']) ? $_GET['cari'] : "";

    if ($idPertemuan == 0 || $idKelas == 0) {
        pindahHalaman("/guru/kelas");
    }

    $pertemuan = getPertemuan($idPertemuan);
    $arr = getSemuaAbsensi($idPertemuan);
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
                <a href="../pertemuan/index.php?i=<?php echo $idPertemuan; ?>&k=<?php echo $idKelas; ?>" class="btn btn-outline-secondary">
                    <span data-feather="arrow-left"></span>
                </a>
                <h1 class="h5 w-50 title">
                    <?php echo $pertemuan['nama']; ?><br>
                    <small class="text-muted"><?php echo $pertemuan['deskripsi']; ?></small>
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method="GET" action="<?php echo BASE_URL . '/guru/absensi/'; ?>" class="btn-group mr-2">
                        <input type="hidden" name="k" value="<?php echo $idKelas; ?>">
                        <input type="hidden" name="i" value="<?php echo $idPertemuan; ?>">
                        <input type="text" name="cari" id="cari" class="form-control" placeholder="Nama murid" value="<?php echo $cari; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <span data-feather="search"></span>
                        </button>
                    </form>
                    <a href="print.php?i=<?php echo $idPertemuan; ?>&k=<?php echo $idKelas; ?>" class="btn btn-outline-secondary">
                        <span data-feather="printer"></span>
                    </a>
                </div>
            </div>

            <?php if(count($arr['data']) > 0 ) { ?>
                <p><?php echo empty($cari) ? "" : "Terdapat " . $arr['jumlah'] . " murid dengan nama \"" . $cari . "\""; ?></p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" width="">No</th>
                                <th scope="col" width="">Nama</th>
                                <th scope="col" width="">Keterangan</th>
                                <th scope="col" width="2%">Validitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arr['data'] as $i => $absensi) { ?>
                                <tr>
                                    <td><?php echo ($i + 1); ?></td>
                                    <td class="<?php echo $absensi['status'] != NULL ? getBgKeterangan($absensi['status']) : ""; ?>">
                                        <?php echo $absensi['nama']; ?>
                                    </td>
                                    <td>
                                        <form id="form<?php echo ($i + 1);?>" method="POST" action="ubah-keterangan.php">
                                            <input type="hidden" name="id_pengguna" value="<?php echo $absensi['id_pengguna']; ?>">
                                            <input type="hidden" name="id_pertemuan" value="<?php echo $absensi['id_pertemuan']; ?>">
                                            <input type="hidden" name="id_kelas" value="<?php echo $idKelas; ?>">
                                            <select name="status" class="custom-select keterangan" id="<?php echo ($i + 1); ?>">
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
                                            <div class="btn btn-outline-success btn-valid cursor-pointer" href="<?php echo BASE_URL . '/guru/absensi/konfirmasi.php?i=' . $absensi['id_pertemuan'] . '&k=' . $idKelas . '&pn=' . $absensi['id_pengguna']; ?>">
                                                <span data-feather="x" class="text-danger"></span>
                                            </div>
                                        <?php } else { ?>
                                            <span data-feather="alert-triangle" class="text-warning"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p>Murid dengan nama "<?php echo $cari; ?>" tidak ditemukan.</p>
            <?php } ?>
        </main>

        <?php 
            bootstrapFooter(); 
            pesan();
        ?>

        <script src="<?php echo BASE_URL . '/assets/js/hapus.js'; ?>"></script>
        <script src="<?php echo BASE_URL . '/assets/js/absensi.js'; ?>"></script>
        <script>
            addHapusBtns();
            addEventKeterangan("Ubah keterangan absensi?");
            addEventValid("Konfirmasi keterangan absensi murid ini?");
        </script>
    </body>
</html>