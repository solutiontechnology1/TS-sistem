<?php
session_start();

$timeout_duration = 300;

// Semak jika pengguna login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Semak masa aktiviti terakhir
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();     // buang semua data session
    session_destroy();   // tamatkan sesi
    header("Location: logout.php?timeout=1");
    exit;
}

// Update masa aktiviti terakhir
$_SESSION['LAST_ACTIVITY'] = time();

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$filename = "G:\Shared drives\SUMBER MANUSIA\INFO KAKITANGAN\CLAIM MILEAGE.xlsx";

try {
    $spreadsheet = IOFactory::load($filename);
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);

    $startRow = 1;
    for ($i = 1; $i <= 5; $i++) {
        if (isset($data[$i]['A']) && $data[$i]['A'] == 'BIL' && isset($data[$i]['B']) && $data[$i]['B'] == 'NAMA') {
            $startRow = $i;
            break;
        }
    }

} catch (Exception $e) {
    die("Error loading file: " . $e->getMessage());
}


// FUNCTION UNTUK FORMAT ANGKA
function formatNumber($value) {
    if (empty($value) || $value === '') return '';
    if (!is_numeric($value)) return $value;
    
    // Bersihkan angka floating point
    $cleanValue = round(floatval($value), 2);
    
    // Hilangkan .00 jika angka bulat
    if ($cleanValue == intval($cleanValue)) {
        return intval($cleanValue);
    }
    
    return number_format($cleanValue, 2, '.', '');
}

// PROSES PENCARIAN
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredData = [];

if (!empty($searchTerm)) {
    foreach ($data as $index => $row) {
        if ($index <= $startRow) continue;
        if (empty($row['A']) && empty($row['B'])) continue;
        
        // Cari di semua kolom teks (nama dan bulan)
        $found = false;
        foreach ($row as $key => $value) {
            // Perbaikan: Pastikan value adalah string sebelum menggunakan stripos
            if ($value !== null && is_string($value) && stripos($value, $searchTerm) !== false) {
                $found = true;
                break;
            }
        }
        
        if ($found) {
            $filteredData[$index] = $row;
        }
    }
} else {
    // Jika tidak ada pencarian, gunakan semua data
    foreach ($data as $index => $row) {
        if ($index <= $startRow) continue;
        if (empty($row['A']) && empty($row['B'])) continue;
        $filteredData[$index] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Senarai Mileage Claim - KERETA</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 10px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-info { background-color: #17a2b8; color: white; }
        .number { text-align: right; font-family: monospace; }
        .search-container { margin-bottom: 20px; display: flex; align-items: center; }
        .search-input { padding: 8px; width: 300px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .search-btn { padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-btn:hover { background-color: #45a049; }
        .reset-btn { padding: 8px 16px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px; text-decoration: none; display: inline-block; }
        .reset-btn:hover { background-color: #d32f2f; }
    </style>
</head>
<body>
    <h2>Senarai Mileage Claim - KERETA</h2>
    <div style="margin-bottom: 15px;">
    <a href="logout.php" class="btn btn-info" style="float: right;">Logout</a>
</div>

    <div style="margin-bottom: 15px;">
        <a href="insert.php" class="btn btn-primary">Tambah Baru (KERETA)</a>
        <a href="view_motor.php" class="btn btn-success" style="margin-left: 10px;">Lihat Claim MOTOR</a>
    </div>
    
    <!-- Form Pencarian -->
    <div class="search-container">
        <form method="GET" action="" style="display: flex; align-items: center;">
            <input type="text" name="search" class="search-input" placeholder="Cari nama atau nilai..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="search-btn">Cari</button>
            <?php if (!empty($searchTerm)): ?>
                <a href="?" class="reset-btn">Reset</a>
            <?php endif; ?>
        </form>
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
        $rowCount = 0;
        // --- PAGINATION SETUP --- //
        $recordsPerPage = 20;
        $totalRecords = count($filteredData);
        $totalPages = ceil($totalRecords / $recordsPerPage);

        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $startIndex = ($currentPage - 1) * $recordsPerPage;
        $filteredData = array_slice($filteredData, $startIndex, $recordsPerPage, true);

        foreach ($filteredData as $index => $row) {
            $foundData = true;
            $rowCount++;
            
            echo "<tr>
                    <td>" . (!empty($row['A']) ? $row['A'] : '') . "</td>
                    <td>" . (!empty($row['B']) ? $row['B'] : '') . "</td>
                    <td class='number'>" . formatNumber($row['C']) . "</td>
                    <td class='number'>" . formatNumber($row['D']) . "</td>
                    <td class='number'>" . formatNumber($row['E']) . "</td>
                    <td class='number'>" . formatNumber($row['F']) . "</td>
                    <td class='number'>" . formatNumber($row['G']) . "</td>
                    <td class='number'>" . formatNumber($row['H']) . "</td>
                    <td class='number'>" . formatNumber($row['I']) . "</td>
                    <td class='number'>" . formatNumber($row['J']) . "</td>
                    <td class='number'>" . formatNumber($row['K']) . "</td>
                    <td class='number'>" . formatNumber($row['L']) . "</td>
                    <td class='number'>" . formatNumber($row['M']) . "</td>
                    <td>
                        <a href='update.php?id=" . (!empty($row['A']) ? $row['A'] : 'new') . "&row=$index'>Update</a>
                    </td>
                </tr>";
            
        }
        
        if (!$foundData) {
            echo '<tr><td colspan="14" style="text-align: center; color: red;">Tiada data ditemukan.</td></tr>';
        }
        ?>
    </table>
    <?php if ($totalPages > 1): ?>
    <div style="margin-top: 20px; text-align: center;">
        <?php if ($currentPage > 1): ?>
            <a class="btn btn-info" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
        <?php endif; ?>

        <span style="margin: 0 10px;">Halaman <?php echo $currentPage; ?> dari <?php echo $totalPages; ?></span>

        <?php if ($currentPage < $totalPages): ?>
            <a class="btn btn-info" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div style="margin-top: 20px; color: green;">
        <strong>Data ditemukan: <?php echo $rowCount; ?> baris</strong>
        <?php if (!empty($searchTerm)): ?>
            <span style="color: #666;">(Hasil pencarian untuk: "<?php echo htmlspecialchars($searchTerm); ?>")</span>
        <?php endif; ?>
    </div>

    <script>
    let idleTime = 0;
    const maxIdleTime = 300; // 300 saat = 5 minit

    // Reset timer bila pengguna bergerak atau buat input
    function resetIdleTime() {
        idleTime = 0;
    }

    // Event listener untuk kesan aktiviti
    window.onload = resetIdleTime;
    window.onmousemove = resetIdleTime;
    window.onkeydown = resetIdleTime;
    window.onclick = resetIdleTime;
    window.onscroll = resetIdleTime;

    // Setiap 1 saat, kira idle time
    setInterval(() => {
        idleTime++;
        if (idleTime >= maxIdleTime) {
            alert("Sesi anda telah tamat kerana tiada aktiviti.");
            window.location.href = "logout.php?timeout=1";
        }
    }, 1000);
</script>
</body>
</html>