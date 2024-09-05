<?php
session_start();

// Ühendus andmebaasiga
$host = 'localhost';
$user = 'krista';
$password = 'krista';
$database = 'restod';

$yhendus = mysqli_connect($host, $user, $password, $database);

if (!$yhendus) {
    die("Connection failed: " . mysqli_connect_error());
}

// Otsingu funktsioon
$otsi = $_GET['otsi'] ?? '';

$perPage = 10;

if (isset($_GET['next'])) {
    $currentPage = (int)$_GET['next'];
} elseif (isset($_GET['prev'])) {
    $currentPage = max(1, (int)$_GET['prev']);
} else {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $perPage;

// Sorteerimisalus
$sort = $_GET['sort'] ?? 'resto';
$order = $_GET['order'] ?? 'ASC';

// päring tabelist
$paring = "SELECT * FROM esirestod"; 

// Otsingufilter
if (!empty($otsi)) {
    $paring .= ' WHERE resto LIKE "%' . mysqli_real_escape_string($yhendus, $otsi) . '%"';
}

$paring .= " ORDER BY $sort $order LIMIT $offset, $perPage";

$valjund = mysqli_query($yhendus, $paring);

if (!$valjund) {
    die("Query failed: " . mysqli_error($yhendus));
}
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tieto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sort-link {
            color: white;
            text-decoration: none;
        }
        .sort-link:hover {
            text-decoration: underline;
        }

        .table-dark {
            background-color: #343a40;
        }
        .table-dark th {
            color: white;
        }
    </style>
</head>
<body>
<!-- nav bar -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Tieto töö</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Avaleht</a>
            </li>
            <?php if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']): ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Admin Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Valige asutus mida hinnata</h1>
    
    <!-- Otsinguriba -->
    <div class="d-flex justify-content-end mt-3 mb-3">
        <form class="d-flex" method="GET" action="" style="width: 300px;">
            <input class="form-control me-2" type="search" placeholder="Otsi toidukohta..." aria-label="Search" name="otsi" value="<?= htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-outline-primary" type="submit">Otsi</button>
        </form>
    </div>

    <?php
    // Tabel
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped">';
    echo '<thead class="table-dark"><tr>';
    echo '<th><a href="?sort=resto&order=' . ($order === 'ASC' ? 'DESC' : 'ASC') . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '" class="sort-link">Resto</a></th>';
    echo '<th><a href="?sort=asukoht&order=' . ($order === 'ASC' ? 'DESC' : 'ASC') . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '" class="sort-link">Asukoht</a></th>';
    echo '<th><a href="?sort=keskmine&order=' . ($order === 'ASC' ? 'DESC' : 'ASC') . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '" class="sort-link">Keskmine</a></th>';
    echo '<th><a href="?sort=hinnatud&order=' . ($order === 'ASC' ? 'DESC' : 'ASC') . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '" class="sort-link">Hinnatud</a></th>';
    echo '</tr></thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($valjund)) {
        
        $resto = htmlspecialchars($row['resto'] ?? '', ENT_QUOTES, 'UTF-8');
        $asukoht = htmlspecialchars($row['asukoht'] ?? '', ENT_QUOTES, 'UTF-8');
        $keskmine = htmlspecialchars($row['keskmine'] ?? '', ENT_QUOTES, 'UTF-8');
        $hinnatud = htmlspecialchars($row['hinnatud'] ?? '', ENT_QUOTES, 'UTF-8');
        $id = htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8');

        echo '<tr>';
        echo '<td><a href="rate_restaurant.php?id=' . $id . '" style="color: black;">' . $resto . '</a></td>';
        echo '<td>' . $asukoht . '</td>';
        echo '<td>' . $keskmine . '</td>';
        echo '<td>' . $hinnatud . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
// Eelmine/järgmine leht nupud
    echo '<nav>';
    echo '<ul class="pagination">';
    echo '<li class="page-item' . ($currentPage == 1 ? ' disabled' : '') . '"><a class="page-link" href="?prev=' . ($currentPage - 1) . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '">« Eelmine</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?next=' . ($currentPage + 1) . '&otsi=' . htmlspecialchars($otsi, ENT_QUOTES, 'UTF-8') . '">Järgmine »</a></li>';
    echo '</ul>';
    echo '</nav>';

    // Andmebaasiühenduse katkestamine
    mysqli_close($yhendus);
    ?>
</div>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Krista Kutsar ITS-23</span>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Ejt6xkk+VZB+Mvo5M5llgz1TOwCd" crossorigin="anonymous"></script>
</body>
</html>
