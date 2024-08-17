<?php
session_start();

// Et kasutajal on õigused ligipääsemiseks
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$user = 'krista';
$password = 'krista';
$database = 'restod';

$yhendus = mysqli_connect($host, $user, $password, $database);

if (!$yhendus) {
    die("Connection failed: " . mysqli_connect_error());
}

// Välja logimine
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Destroy session and redirect to index.php
    session_destroy();
    header("Location: index.php");
    exit();
}

// Kustutamine
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_query = "DELETE FROM esirestod WHERE id = $delete_id";

    if (mysqli_query($yhendus, $delete_query)) {
        header("Location: add_edit.php"); // Redirect back to the page after deletion
        exit();
    } else {
        die("Error deleting record: " . mysqli_error($yhendus));
    }
}


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$resto = $asukoht = '';


if ($id) {
    $query = "SELECT * FROM esirestod WHERE id = $id";
    $result = mysqli_query($yhendus, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $resto = htmlspecialchars($row['resto']);
        $asukoht = htmlspecialchars($row['asukoht']);
    } else {
        die("Record not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    $resto = mysqli_real_escape_string($yhendus, $_POST['resto']);
    $asukoht = mysqli_real_escape_string($yhendus, $_POST['asukoht']);

    if ($id) {
        // Uuendab andmeid
        $query = "UPDATE esirestod SET resto='$resto', asukoht='$asukoht' WHERE id=$id";
    } else {
        // Lisab uued andmed
        $query = "INSERT INTO esirestod (resto, asukoht) VALUES ('$resto', '$asukoht')";
    }

    if (mysqli_query($yhendus, $query)) {
        header("Location: add_edit.php"); 
        exit();
    } else {
        die("Error: " . mysqli_error($yhendus));
    }
}


$limit = 10;  
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$count_query = "SELECT COUNT(*) AS total FROM esirestod";
$count_result = mysqli_query($yhendus, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $limit);


$restaurants_query = "SELECT * FROM esirestod LIMIT $limit OFFSET $offset";
$restaurants_result = mysqli_query($yhendus, $restaurants_query);

mysqli_close($yhendus);
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Restaurants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        
        .action-link {
            color: black;
            text-decoration: underline;
            cursor: pointer;
        }
        .action-link:hover {
            color: darkgray;
        }
    </style>
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
                <form method="POST" action="add_edit.php" style="display:inline;">
                    <button type="submit" name="logout" class="btn btn-link">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1><?= $id ? 'Edit' : 'Lisa' ?> restoran</h1>
    <form method="POST" action="add_edit.php?id=<?= $id ?>">
        <div class="mb-3">
            <label for="resto" class="form-label">Restorani nimi</label>
            <input type="text" class="form-control" id="resto" name="resto" value="<?= $resto ?>" required>
        </div>
        <div class="mb-3">
            <label for="asukoht" class="form-label">Asukoht</label>
            <input type="text" class="form-control" id="asukoht" name="asukoht" value="<?= $asukoht ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= $id ? 'Update' : 'Lisa' ?></button>
    </form>

    <hr>

    <h2 class="mt-5">Halda restorane</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Resto</th>
                <th>Asukoht</th>
                <th>Muuda</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($restaurants_result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['resto']) ?></td>
                    <td><?= htmlspecialchars($row['asukoht']) ?></td>
                    <td>
                        <a href="add_edit.php?id=<?= $row['id'] ?>" class="action-link">Muuda</a> |
                        <a href="add_edit.php?delete=<?= $row['id'] ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this restaurant?');">Kustuta</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="add_edit.php?page=<?= $page - 1 ?>">Eelmine</a>
            </li>
            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="add_edit.php?page=<?= $page + 1 ?>">Järgmine</a>
            </li>
        </ul>
    </nav>
</div>
<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Krista Kutsar ITS-23</span>
    </footer>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Ejt6xkk+VZB+Mvo5M5llgz1TOwCd" crossorigin="anonymous"></script>
</body>
</html>
