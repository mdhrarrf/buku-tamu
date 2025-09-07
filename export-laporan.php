<?php
include('koneksi.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'TANGGAL');
$sheet->setCellValue('C1', 'NAMA TAMU');
$sheet->setCellValue('D1', 'ALAMAT');
$sheet->setCellValue('E1', 'NO. TELEPON/HP');
$sheet->setCellValue('F1', 'BERTEMU DENGAN');
$sheet->setCellValue('G1', 'KEPENTINGAN');

if (isset($_GET['cari'])) {
    $p_awal = $_GET['p_awal'];
    $p_akhir = $_GET['p_akhir'];
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir'");
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu");
}

$i = 2;
$no = 1;
while ($row = mysqli_fetch_array($data)) {
    $sheet->setCellValue('A' . $i, $no);
    $sheet->setCellValue('B' . $i, $row['tanggal']);
    $sheet->setCellValue('C' . $i, $row['nama_tamu']);
    $sheet->setCellValue('D' . $i, $row['alamat']);
    $sheet->setCellValue('E' . $i, $row['no_hp']);
    $sheet->setCellValue('F' . $i, $row['bertemu']);
    $sheet->setCellValue('G' . $i, $row['kepentingan']);
    $i++;
    $no++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('LAPORAN BUKU TAMU.xlsx');
echo "<script>window.location = 'LAPORAN BUKU TAMU.xlsx'</script>";
?>