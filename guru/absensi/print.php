<?php
    require_once("../../bantuan/functions.php");
    require_once("../../bantuan/print.php");
    require_once("../../assets/php/fpdf182/fpdf.php");

    if (!isset($_GET['i']) || !isset($_GET['k'])) 
        pindahHalaman("/guru/absensi/index.php?i=" . $idPertemuan . "&k=" . $idKelas);

    $pertemuan = getPertemuan($_GET['i'], $_GET['k']);
    $absensi = getAbsensi($_GET['i'], $_GET['k']);

    class PDF extends FPDF {
        public function __construct($data) {
            parent::__construct();
            $this->data = $data;
        }

        function Header() {
            $this->Image("../../assets/img/logo.png", 170, 10, 30);

            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, "Daftar Absensi", 0, 0, 'L');
            $this->Ln();

            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 6, $this->data['namaSekolah'], 0, 0, 'L');
            $this->Ln();

            $this->Cell(0, 6, $this->data["namaKelas"] . " | " . $this->data["namaPertemuan"], 0, 0, 'L');
            $this->Ln();

            $this->Cell(0, 6, formatTanggal($this->data["tanggal_mulai"]) . " " . formatWaktu($this->data["tanggal_mulai"]) . " - " . formatWaktu($this->data["tanggal_selesai"]));
            $this->Ln();

            $this->Cell(0, 6, $this->data["namaGuru"]);
            $this->Ln(10);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
        }

        function createTable($header, $data) {
            $this->SetFillColor(0,0,0);
            $this->SetTextColor(255);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');

            $w = array(10, 140, 40);
            foreach($header as $i => $h) {
                $this->Cell($w[$i], 7, $h, 1, 0, 'C', true);
            }
            $this->Ln();

            $this->SetFillColor(255, 255, 255);
            $this->SetTextColor(0);
            $this->SetFont('');

            // $fill = false;
            foreach($data as $row) {
                $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', true);
                $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', true);
                $this->SetFillColor($row[3][0], $row[3][1], $row[3][2]);
                $this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', true);
                $this->SetFillColor(255, 255, 255);
                $this->Ln();
                // $fill = !$fill;
            }

            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }

    $pdf = new PDF($pertemuan);

    $header = array('No', 'Nama', 'Keterangan');
    $data = array();

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
    $pdf->createTable($header, $data);
    $pdf->Ln(10);
    $pdf->Cell(0, 6, "Dicetak pada tanggal: " . formatTanggal(date("Y/m/d")));
    $pdf->Output();
?>