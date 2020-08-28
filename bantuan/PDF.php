<?php
    require_once("../../assets/php/fpdf182/fpdf.php");
    class PDF extends FPDF {
        public function __construct($data, $tipe = 1) {
            parent::__construct();
            $this->data = $data;
            $this->tipe = $tipe;
        }

        function Header() {
            $namaSekolah = isset($this->data["namaSekolah"]) ? $this->data["namaSekolah"] : "";
            $namaKelas = isset($this->data["namaKelas"]) ? $this->data["namaKelas"] : "";
            $namaPertemuan = isset($this->data["namaPertemuan"]) ? $this->data["namaPertemuan"] : "";
            $tanggalMulai = isset($this->data["tanggalMulai"]) ? $this->data["tanggalMulai"] : "";
            $tanggalSelesai = isset($this->data["tanggalSelesai"]) ? $this->data["tanggalSelesai"] : "";
            $namaGuru = isset($this->data["namaGuru"]) ? $this->data["namaGuru"] : "";
            $kelasMulai = isset($this->data["kelasMulai"]) ? $this->data["kelasMulai"] : "";
            $kelasSelesai = isset($this->data["kelasSelesai"]) ? $this->data["kelasSelesai"] : "";

            $this->Image("../../assets/img/logo.png", 170, 10, 30);

            switch($this->tipe) {
                case 1:
                    $this->SetFont('Arial', 'B', 15);
                    $this->Cell(0, 10, "Laporan Absensi", 0, 0, 'L');
                    $this->Ln();

                    $this->SetFont('Arial', '', 12);
                    $this->Cell(0, 6, $namaSekolah, 0, 0, 'L');
                    $this->Ln();

                    $this->Cell(0, 6, $namaKelas . " | " . $namaPertemuan, 0, 0, 'L');
                    $this->Ln();

                    $this->Cell(0, 6, formatTanggal($tanggalMulai) . " " . formatWaktu($tanggalMulai) . " - " . formatWaktu($tanggalSelesai));
                    $this->Ln();

                    $this->Cell(0, 6, $namaGuru);
                    $this->Ln(10);
                    break;
                case 2:
                    $this->SetFont('Arial', 'B', 15);
                    $this->Cell(0, 10, "Laporan Kehadiran", 0, 0, 'L');
                    $this->Ln();

                    $this->SetFont('Arial', '', 12);
                    $this->Cell(0, 6, $namaSekolah, 0, 0, 'L');
                    $this->Ln();

                    $this->Cell(0, 6, $namaKelas, 0, 0, 'L');
                    $this->Ln();

                    $this->Cell(0, 6, formatTanggal($kelasMulai) . " - " . formatTanggal($kelasSelesai));
                    $this->Ln();

                    $this->Cell(0, 6, $namaGuru);
                    $this->Ln(10);
                    break;
            }
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
        }

        function createTable($header, $data, $w, $tipe = 1) {
            $this->SetFillColor(0,0,0);
            $this->SetTextColor(255);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');

            foreach($header as $i => $h) {
                $this->Cell($w[$i], 7, $h, 1, 0, 'C', true);
            }
            $this->Ln();

            $this->SetFillColor(255, 255, 255);
            $this->SetTextColor(0);
            $this->SetFont('');

            switch($tipe) {
                case 1:
                    foreach($data as $row) {
                        $r = isset($row[3][0]) ? $row[3][0] : 255;
                        $g = isset($row[3][1]) ? $row[3][1] : 255;
                        $b = isset($row[3][2]) ? $row[3][2] : 255;

                        $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', true);
                        $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', true);
                        $this->SetFillColor($r, $g, $b);
                        $this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', true);
                        $this->SetFillColor(255, 255, 255);
                        $this->Ln();
                    }
                    break;
                case 2:
                    foreach($data as $row) {
                        foreach($row as $i => $r) {
                            $this->Cell($w[$i], 6, $r, 'LR', 0, 'C', true);
                        }
                        $this->Ln();
                    }
            }

            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }
?>