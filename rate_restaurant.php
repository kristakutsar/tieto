<?php
session_start();

// andmebaasi ühendus
$host = 'localhost';
$user = 'krista';
$password = 'krista';
$database = 'restod';

$yhendus = mysqli_connect($host, $user, $password, $database);

if (!$yhendus) {
    die("Connection failed: " . mysqli_connect_error());
}

// muutujate määramine
$nimi = '';
$kommentaar = '';
$hinnang = '';
$errors = [];

// Vormi loomine
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = mysqli_real_escape_string($yhendus, trim($_POST['nimi']));
    $kommentaar = mysqli_real_escape_string($yhendus, trim($_POST['kommentaar']));
    $hinnang = isset($_POST['hinnang']) ? (int)$_POST['hinnang'] : '';

    if (empty($nimi)) {
        $errors['nimi'] = 'Nimi on kohustuslik';
    }
    if (empty($kommentaar)) {
        $errors['kommentaar'] = 'Kommentaar on kohustuslik';
    }
    if (empty($hinnang) || $hinnang < 1 || $hinnang > 10) {
        $errors['hinnang'] = 'Hinnang peab olema vahemikus 1 kuni 10';
    }

    // Kui vigu pole, sisestab andmed andmebaasi
    if (empty($errors)) {
        $restoId = mysqli_real_escape_string($yhendus, $_GET['id']);
        $insertQuery = "INSERT INTO hinnangud (resto_id, nimi, kommentaar, hinnang) VALUES ('$restoId', '$nimi', '$kommentaar', '$hinnang')";
        if (mysqli_query($yhendus, $insertQuery)) {
            // Uuendab restoranide keskmisthinnet ja hinnangute arvu
            $updateQuery = "UPDATE esirestod 
                            SET keskmine = (SELECT AVG(hinnang) FROM hinnangud WHERE resto_id = '$restoId'),
                                hinnatud = (SELECT COUNT(*) FROM hinnangud WHERE resto_id = '$restoId')
                            WHERE id = '$restoId'";
            mysqli_query($yhendus, $updateQuery);

            // Tagasi pealehele
            header("Location: index.php");
            exit();
        } else {
            $errors['database'] = 'Andmete salvestamine ebaõnnestus: ' . mysqli_error($yhendus);
        }
    }
}

// Võtab andmebaaasist olevad hinnangud
$restoId = mysqli_real_escape_string($yhendus, $_GET['id']);
$ratingsQuery = "SELECT nimi, kommentaar, hinnang FROM hinnangud WHERE resto_id = '$restoId' ORDER BY id DESC";
$ratingsResult = mysqli_query($yhendus, $ratingsQuery);
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
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
    <h1>Hinda Restorani</h1>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nimi" class="form-label">Nimi</label>
            <input type="text" class="form-control <?= isset($errors['nimi']) ? 'is-invalid' : '' ?>" id="nimi" name="nimi" value="<?= htmlspecialchars($nimi, ENT_QUOTES, 'UTF-8') ?>">
            <div class="invalid-feedback"><?= $errors['nimi'] ?? '' ?></div>
        </div>
        <div class="mb-3">
            <label for="kommentaar" class="form-label">Kommentaar</label>
            <textarea class="form-control <?= isset($errors['kommentaar']) ? 'is-invalid' : '' ?>" id="kommentaar" name="kommentaar"><?= htmlspecialchars($kommentaar, ENT_QUOTES, 'UTF-8') ?></textarea>
            <div class="invalid-feedback"><?= $errors['kommentaar'] ?? '' ?></div>
        </div>
        <div class="mb-3">
            <label for="hinnang" class="form-label">Hinnang (1-10)</label>
            <div>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input <?= isset($errors['hinnang']) ? 'is-invalid' : '' ?>" type="radio" name="hinnang" id="hinnang<?= $i ?>" value="<?= $i ?>" <?= $hinnang == $i ? 'checked' : '' ?>>
                        <label class="form-check-label" for="hinnang<?= $i ?>"><?= $i ?></label>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="invalid-feedback"><?= $errors['hinnang'] ?? '' ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Saada hinnang</button>
        <a href="index.php" class="btn btn-secondary">Tagasi avalehele</a>
    </form>

    <h2 class="mt-5">Teiste hinnangud</h2>

    <?php if (mysqli_num_rows($ratingsResult) > 0): ?>
        <ul class="list-group">
            <?php while ($ratingRow = mysqli_fetch_assoc($ratingsResult)): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($ratingRow['nimi'], ENT_QUOTES, 'UTF-8') ?>:</strong> <?= htmlspecialchars($ratingRow['kommentaar'], ENT_QUOTES, 'UTF-8') ?>
                    <span class="badge bg-primary float-end"><?= htmlspecialchars($ratingRow['hinnang'], ENT_QUOTES, 'UTF-8') ?>/10</span>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Veel pole ühtegi hinnangut.</p>
    <?php endif; ?>

</div>
<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Krista Kutsar ITS-23</span>
    </footer>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Ejt6xkk+VZB+Mvo5M5llgz1TOwCd" crossorigin="anonymous"></script>
</body>
</html>

<?php

mysqli_close($yhendus);
?>
