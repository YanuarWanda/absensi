<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/print.php");
    require_once("../../bantuan/PDF.php");

    if (!isset($_GET['i']) || !isset($_GET['k'])) 
        pindahHalaman("/guru/absensi/index.php?i=" . $idPertemuan . "&k=" . $idKelas);

    $pertemuan = getPertemuan($_GET['i'], $_GET['k']);
    $absensi = getAbsensi($_GET['i'], $_GET['k']);

    $jumlah_hadir = 0;
    $jumlah_sakit = 0;
    $jumlah_izin = 0;
    $jumlah_alpa = 0;
    foreach($absensi as $a) {
        switch($a["status"]) {
            case "H": 
                $jumlah_hadir++;
                break;
            case "S":
                $jumlah_sakit++;
                break;
            case "I":
                $jumlah_izin++;
                break;
            case "A":
            default: 
                $jumlah_alpa++;
                break;
        }
    }
    
    $jumlah_murid = count($absensi);

    $pdf = new PDF($pertemuan);

    $header = array('No', 'Nama', 'Keterangan');
    $data = array();
    $w = array(10, 140, 40);

    foreach($absensi as $i => $a) {
        $bgColor = array(255, 255, 255);

        switch ($a["status"]) {
            case "H": 
                $bgColor = array(0, 255, 0);
                break;
            case "I":
                $bgColor = array(255, 255, 0);
                break;
            case "S":
                $bgColor = array(255, 0, 0);
                break;
            case "A":
                $bgColor = array(255, 255, 255);
                break;
        }

        array_push($data, array(
            ($i + 1), $a["nama"], getKeterangan($a["status"]), $bgColor
        ));
    }

    $pdf->AliasNbPages();
    $pdf->SetFont('Times', '', 12);
    $pdf->AddPage();
    $pdf->createTable($header, $data, $w);
    $pdf->Ln(6);

    $header2 = array("", "Hadir", "Sakit", "Izin", "Alpa", "Jumlah");
    $data2 = array(
        array(
            "Persentase", 
            persentaseKehadiran($jumlah_hadir, $jumlah_murid), 
            persentaseKehadiran($jumlah_sakit, $jumlah_murid), 
            persentaseKehadiran($jumlah_izin, $jumlah_murid),
            persentaseKehadiran($jumlah_alpa, $jumlah_murid),
            "100%"
        ),
        array(
            "Jumlah",
            $jumlah_hadir,
            $jumlah_sakit,
            $jumlah_izin,
            $jumlah_alpa,
            $jumlah_murid
        )
    );
    $w2 = array(40, 30, 30, 30, 30, 30);

    $pdf->createTable($header2, $data2, $w2, 2);
    $pdf->Ln(6);

    $pdf->Cell(0, 6, "Dicetak pada tanggal: " . formatTanggal(date("Y/m/d")));
    
    $fileName = "Laporan_Absensi-" . $pertemuan["namaKelas"] . "-" . $pertemuan["namaPertemuan"]. "-" . formatTanggalFile(date("Y/m/d")) . ".pdf";
    $pdf->Output("D", $fileName);
?>