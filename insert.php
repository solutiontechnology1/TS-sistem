<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// FUNCTION UNTUK FORMAT ANGKA - LETAK DI SINI JUGA
function formatNumber($value) {
    if (empty($value) || $value === '') return '';
    if (!is_numeric($value)) return $value;
    
    $cleanValue = round(floatval($value), 2);
    if ($cleanValue == intval($cleanValue)) {
        return intval($cleanValue);
    }
    return number_format($cleanValue, 2, '.', '');
}

$filename = "G:\Shared drives\SUMBER MANUSIA\INFO KAKITANGAN\CLAIM MILEAGE.xlsx";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $spreadsheet = IOFactory::load($filename);
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $highestRow = $sheet->getHighestRow();
        $newRow = $highestRow + 1;

        $maxId = 0;
        foreach ($data as $index => $row) {
            if ($index > 2 && is_numeric($row['A']) && $row['A'] > $maxId) {
                $maxId = $row['A'];
            }
        }
        $newId = $maxId + 1;

        // Gunakan rounding ketika menyimpan
        $sheet->setCellValue("A$newRow", $newId);
        $sheet->setCellValue("B$newRow", $_POST['nama']);
        $sheet->setCellValue("C$newRow", round(floatval($_POST['feb']), 2));
        $sheet->setCellValue("D$newRow", round(floatval($_POST['mar']), 2));
        $sheet->setCellValue("E$newRow", round(floatval($_POST['apr']), 2));
        $sheet->setCellValue("F$newRow", round(floatval($_POST['may']), 2));
        $sheet->setCellValue("G$newRow", round(floatval($_POST['jun']), 2));
        $sheet->setCellValue("H$newRow", round(floatval($_POST['jul']), 2));
        $sheet->setCellValue("I$newRow", round(floatval($_POST['aug']), 2));
        $sheet->setCellValue("J$newRow", round(floatval($_POST['sep']), 2));
        $sheet->setCellValue("K$newRow", round(floatval($_POST['oct']), 2));
        $sheet->setCellValue("L$newRow", round(floatval($_POST['nov']), 2));
        $sheet->setCellValue("M$newRow", round(floatval($_POST['dec']), 2));

        $tempFile = "G:\Shared drives\SUMBER MANUSIA\INFO KAKITANGAN\CLAIM MILEAGE.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        rename($tempFile, $filename);

        header("Location: index.php");
        exit;
        
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Claim - KERETA</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .month-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .month-item { border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0069d9; }
    </style>
</head>
<body>
    <h2>Tambah Claim Mileage - KERETA</h2>
    <form method="post">
        <div class="form-group">
            <label for="nama">NAMA:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        
        <h3>Jumlah Claim Mengikut Bulan (RM):</h3>
        <div class="month-grid">
            <div class="month-item">
                <label for="feb">FEB:</label>
                <input type="number" step="0.01" name="feb" value="0">
            </div>
            <div class="month-item">
                <label for="mar">MAR:</label>
                <input type="number" step="0.01" name="mar" value="0">
            </div>
            <div class="month-item">
                <label for="apr">APR:</label>
                <input type="number" step="0.01" name="apr" value="0">
            </div>
            <div class="month-item">
                <label for="may">MAY:</label>
                <input type="number" step="0.01" name="may" value="0">
            </div>
            <div class="month-item">
                <label for="jun">JUN:</label>
                <input type="number" step="0.01" name="jun" value="0">
            </div>
            <div class="month-item">
                <label for="jul">JUL:</label>
                <input type="number" step="0.01" name="jul" value="0">
            </div>
            <div class="month-item">
                <label for="aug">AUG:</label>
                <input type="number" step="0.01" name="aug" value="0">
            </div>
            <div class="month-item">
                <label for="sep">SEP:</label>
                <input type="number" step="0.01" name="sep" value="0">
            </div>
            <div class="month-item">
                <label for="oct">OCT:</label>
                <input type="number" step="0.01" name="oct" value="0">
            </div>
            <div class="month-item">
                <label for="nov">NOV:</label>
                <input type="number" step="0.01" name="nov" value="0">
            </div>
            <div class="month-item">
                <label for="dec">DEC:</label>
                <input type="number" step="0.01" name="dec" value="0">
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit">Simpan</button>
            <a href="index.php" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</body>
</html>