<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// FUNCTION UNTUK FORMAT ANGKA - LETAK DI SINI
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

// Cek apakah parameter id ada
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = $_GET['id'];
$rowNumber = null;
$rowData = [];

try {
    $spreadsheet = IOFactory::load($filename);
    $spreadsheet->setActiveSheetIndex(0); // Sheet KERETA
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);

    // Cari data berdasarkan ID
    foreach ($data as $index => $row) {
        if ($index <= 2) continue; // skip header
        if ($row['A'] == $id) {
            $rowNumber = $index;
            $rowData = $row;
            break;
        }
    }

    // Jika data tidak ditemukan
    if (!$rowNumber) {
        die("Data dengan ID $id tidak ditemukan");
    }

} catch (Exception $e) {
    die("Error loading file: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $rowNumber) {
    // Update data - tambah rounding di sini juga
    $sheet->setCellValue("B$rowNumber", $_POST['nama']);
    $sheet->setCellValue("C$rowNumber", round(floatval($_POST['feb']), 2));
    $sheet->setCellValue("D$rowNumber", round(floatval($_POST['mar']), 2));
    $sheet->setCellValue("E$rowNumber", round(floatval($_POST['apr']), 2));
    $sheet->setCellValue("F$rowNumber", round(floatval($_POST['may']), 2));
    $sheet->setCellValue("G$rowNumber", round(floatval($_POST['jun']), 2));
    $sheet->setCellValue("H$rowNumber", round(floatval($_POST['jul']), 2));
    $sheet->setCellValue("I$rowNumber", round(floatval($_POST['aug']), 2));
    $sheet->setCellValue("J$rowNumber", round(floatval($_POST['sep']), 2));
    $sheet->setCellValue("K$rowNumber", round(floatval($_POST['oct']), 2));
    $sheet->setCellValue("L$rowNumber", round(floatval($_POST['nov']), 2));
    $sheet->setCellValue("M$rowNumber", round(floatval($_POST['dec']), 2));

    try {
        $tempFile = "G:\Shared drives\SUMBER MANUSIA\INFO KAKITANGAN\CLAIM MILEAGE.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        rename($tempFile, $filename);

        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        die("Error saving file: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kemaskini Claim - KERETA</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .month-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .month-item { border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h2>Kemaskini Claim KERETA - ID: <?php echo htmlspecialchars($id); ?></h2>
    <form method="post">
        <div class="form-group">
            <label for="nama">NAMA:</label>
            <input type="text" id="nama" name="nama" value="<?php echo isset($rowData['B']) ? htmlspecialchars($rowData['B']) : ''; ?>" required>
        </div>
        
        <h3>Jumlah Claim Mengikut Bulan (RM):</h3>
        <div class="month-grid">
            <div class="month-item">
                <label for="feb">FEB:</label>
                <input type="number" step="0.01" name="feb" value="<?php echo isset($rowData['C']) ? formatNumber($rowData['C']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="mar">MAR:</label>
                <input type="number" step="0.01" name="mar" value="<?php echo isset($rowData['D']) ? formatNumber($rowData['D']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="apr">APR:</label>
                <input type="number" step="0.01" name="apr" value="<?php echo isset($rowData['E']) ? formatNumber($rowData['E']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="may">MAY:</label>
                <input type="number" step="0.01" name="may" value="<?php echo isset($rowData['F']) ? formatNumber($rowData['F']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="jun">JUN:</label>
                <input type="number" step="0.01" name="jun" value="<?php echo isset($rowData['G']) ? formatNumber($rowData['G']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="jul">JUL:</label>
                <input type="number" step="0.01" name="jul" value="<?php echo isset($rowData['H']) ? formatNumber($rowData['H']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="aug">AUG:</label>
                <input type="number" step="0.01" name="aug" value="<?php echo isset($rowData['I']) ? formatNumber($rowData['I']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="sep">SEP:</label>
                <input type="number" step="0.01" name="sep" value="<?php echo isset($rowData['J']) ? formatNumber($rowData['J']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="oct">OCT:</label>
                <input type="number" step="0.01" name="oct" value="<?php echo isset($rowData['K']) ? formatNumber($rowData['K']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="nov">NOV:</label>
                <input type="number" step="0.01" name="nov" value="<?php echo isset($rowData['L']) ? formatNumber($rowData['L']) : ''; ?>">
            </div>
            <div class="month-item">
                <label for="dec">DEC:</label>
                <input type="number" step="0.01" name="dec" value="<?php echo isset($rowData['M']) ? formatNumber($rowData['M']) : ''; ?>">
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit">Update</button>
            <a href="index.php" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</body>
</html>