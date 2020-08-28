<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/murid.php");
    require_once("../../bantuan/PDF.php");

    $idKelas = isset($_GET['k']) ? $_GET['k'] : 0;

    if ($idKelas == 0) {
        pindahHalaman("/guru/murid/index.php?k=" . $idKelas);
    }

    $arr = getSemuaMuridByKelas("", 0, $idKelas);
    $kelas = getKelas($idKelas, true);

    $pdf = new PDF($kelas, 2);

    $header = array('No', 'Nama', 'Kehadiran');
    $data = array();
    foreach($arr["data"] as $i => $a) {
        array_push($data, array(
            ($i + 1), $a['nama'], persentaseKehadiran($a["jumlah_hadir"], $a["jumlah_pertemuan"])
        ));
    }
    $w = array(10, 140, 40);

    $pdf->AliasNbPages();
    $pdf->SetFont('Times', '', 12);
    $pdf->AddPage();
    $pdf->createTable($header, $data, $w);
    $pdf->Ln(6);

    $pdf->Cell(0, 6, "Dicetak pada tanggal: " . formatTanggal(date("Y/m/d")));
    
    $fileName = "Laporan_Kehadiran-" . $kelas["namaKelas"] . "-" . formatTanggalFile(date("Y/m/d")) . ".pdf";
    $pdf->Output("D", $fileName);
?>