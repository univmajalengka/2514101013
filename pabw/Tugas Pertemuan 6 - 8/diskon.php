<?php
session_start();
// ===== Koneksi database (dibuat otomatis kalau belum ada) =====
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = null;
$pesanDb = "";

try {
    $conn = new mysqli("localhost", "root", "");
    $conn->set_charset("utf8mb4");
    $conn->query("CREATE DATABASE IF NOT EXISTS db_diskon");
    $conn->select_db("db_diskon");
    $conn->query("CREATE TABLE IF NOT EXISTS riwayat (
        id INT AUTO_INCREMENT PRIMARY KEY,
        total_belanja DECIMAL(12,2) NOT NULL,
        diskon DECIMAL(12,2) NOT NULL,
        total_bayar DECIMAL(12,2) NOT NULL,
        waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    $conn = null;
    $pesanDb = "Database tidak terhubung, riwayat tidak bisa disimpan.";
}

// ===== Function untuk menghitung diskon =====
function hitungDiskon($totalBelanja) {
    $diskon = 0;

    if ($totalBelanja >= 100000) {
        $diskon = 0.10 * $totalBelanja;  // 10%
    } elseif ($totalBelanja >= 50000) {
        $diskon = 0.05 * $totalBelanja;  // 5%
    }

    return $diskon;
}
// ===== Proses setelah tombol ditekan =====
if (isset($_POST['hitung'])) {
    $totalBelanja = (float) $_POST['totalBelanja'];

    if ($totalBelanja >= 0) {
        $diskon = hitungDiskon($totalBelanja);
        $totalBayar = $totalBelanja - $diskon;

        // Simpan ke database
        if ($conn) {
            $stmt = $conn->prepare("INSERT INTO riwayat (total_belanja, diskon, total_bayar) VALUES (?, ?, ?)");
            $stmt->bind_param("ddd", $totalBelanja, $diskon, $totalBayar);
            $stmt->execute();
            $stmt->close();
        }

        // Simpan hasil sementara, lalu redirect supaya refresh tidak kirim ulang form
        $_SESSION['hasil'] = [
            'total'  => $totalBelanja,
            'diskon' => $diskon,
            'bayar'  => $totalBayar,
        ];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ===== Ambil hasil dari session (kalau ada) untuk ditampilkan =====
$hasil = false;
if (isset($_SESSION['hasil'])) {
    $totalBelanja = $_SESSION['hasil']['total'];
    $diskon       = $_SESSION['hasil']['diskon'];
    $totalBayar   = $_SESSION['hasil']['bayar'];
    $hasil = true;
    unset($_SESSION['hasil']);
}

// ===== Ambil 10 riwayat terakhir =====
$riwayat = [];
if ($conn) {
    $res = $conn->query("SELECT * FROM riwayat ORDER BY id DESC LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $riwayat[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kalkulator Diskon</title>

    <style>
        body {
            font-family: sans-serif;
            background: white;
        }

        .container {
            background: #f4f1e2;
            width: 420px;
            max-width: 90%;
            margin: 60px auto;
            padding: 25px;
            border-radius: 8px;
            box-sizing: border-box;
        }

        h2 { text-align: center; }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #513229;
            color: #f4f1e2;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-sizing: border-box;
        }

        .hasil {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .riwayat { margin-top: 25px; }

        .riwayat h3 { margin-bottom: 8px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }

        th:first-child, td:first-child { text-align: left; }

        th { background: #513229; color: #f4f1e2; }

        .peringatan { color: #a33; font-size: 14px; margin-top: 15px; }
    </style>
</head>

<body>

<div class="container">

    <h2>Kalkulator Diskon</h2>

    <form method="POST">
        <label>Total Belanja</label>

        <input type="number"
               name="totalBelanja"
               placeholder="Masukkan total belanja"
               min="0"
               required>

        <button type="submit" name="hitung">Hitung Diskon</button>
    </form>

    <?php if ($hasil) { ?>
    <div class="hasil">
        <p>Total Belanja: Rp. <?php echo number_format($totalBelanja, 0, ',', '.'); ?></p>
        <p>Diskon: Rp. <?php echo number_format($diskon, 0, ',', '.'); ?></p>
        <p><b>Total yang Harus Dibayar: Rp. <?php echo number_format($totalBayar, 0, ',', '.'); ?></b></p>
    </div>
    <?php } ?>

    <?php if ($pesanDb) { ?>
        <p class="peringatan"><?php echo $pesanDb; ?></p>
    <?php } ?>

    <?php if ($conn && count($riwayat) > 0) { ?>
    <div class="riwayat">
        <h3>Riwayat Perhitungan</h3>
        <table>
            <tr>
                <th>Waktu</th>
                <th>Belanja</th>
                <th>Diskon</th>
                <th>Bayar</th>
            </tr>
            <?php foreach ($riwayat as $r) { ?>
            <tr>
                <td><?php echo date('d/m H:i', strtotime($r['waktu'])); ?></td>
                <td><?php echo number_format($r['total_belanja'], 0, ',', '.'); ?></td>
                <td><?php echo number_format($r['diskon'], 0, ',', '.'); ?></td>
                <td><?php echo number_format($r['total_bayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php } ?>

</div>

</body>
</html>