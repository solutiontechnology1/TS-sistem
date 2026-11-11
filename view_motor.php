<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filename = "G:\Shared drives\SUMBER MANUSIA\INFO KAKITANGAN\CLAIM MILEAGE.xlsx";

try {
    // Load file Excel
    $spreadsheet = IOFactory::load($filename);
    
    // Ambil sheet MOTOR (sheet kedua - index 1)
    $spreadsheet->setActiveSheetIndex(1);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);
    
} catch (Exception $e) {
    die("Error loading file: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Senarai Mileage Claim - MOTOR</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 10px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
    </style>
</head>
<body>
    <h2>Senarai Mileage Claim - MOTOR</h2>
    
    <div style="margin-bottom: 15px;">
        <a href="insert_motor.php" class="btn btn-primary">Tambah Baru (MOTOR)</a>
        <a href="index.php" class="btn btn-success" style="margin-left: 10px;">Lihat Claim KERETA</a>
    </div>
    
    <table>
        <tr>
            <th>BIL</th>
            <th>NAMA</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>APR</th>
            <th>MAY</th>
            <th>JUN</th>
            <th>JUL</th>
            <th>AUG</th>
            <th>SEP</th>
            <th>OCT</th>
            <th>NOV</th>
            <th>DEC</th>
            <th>Tindakan</th>
        </tr>
        <?php
        $foundData = false;
        
        foreach ($data as $index => $row) {
            // Skip header (baris 1 berdasarkan struktur MOTOR)
            if ($index <= 1) continue;
            
            // Skip jika BIL atau NAMA kosong
            if (empty($row['A']) && empty($row['B'])) continue;
            
            $foundData = true;
            
            echo "<tr>
                    <td>" . (!empty($row['A']) ? $row['A'] : '') . "</td>
                    <td>" . (!empty($row['B']) ? $row['B'] : '') . "</td>
                    <td>" . (!empty($row['C']) ? $row['C'] : '') . "</td>
                    <td>" . (!empty($row['D']) ? $row['D'] : '') . "</td>
                    <td>" . (!empty($row['E']) ? $row['E'] : '') . "</td>
                    <td>" . (!empty($row['F']) ? $row['F'] : '') . "</td>
                    <td>" . (!empty($row['G']) ? $row['G'] : '') . "</td>
                    <td>" . (!empty($row['H']) ? $row['H'] : '') . "</td>
                    <td>" . (!empty($row['I']) ? $row['I'] : '') . "</td>
                    <td>" . (!empty($row['J']) ? $row['J'] : '') . "</td>
                    <td>" . (!empty($row['K']) ? $row['K'] : '') . "</td>
                    <td>" . (!empty($row['L']) ? $row['L'] : '') . "</td>
                    <td>" . (!empty($row['M']) ? $row['M'] : '') . "</td>
                    <td>
                        <a href='update_motor.php?id={$row['A']}'>Update</a>
                    </td>
                </tr>";
        }
        
        if (!$foundData) {
            echo '<tr><td colspan="14" style="text-align: center; color: red;">Tiada data ditemukan.</td></tr>';
        }
        ?>
    </table>
</body>
</html>