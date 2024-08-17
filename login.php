<?php
session_start();

$host = 'localhost';
$user = 'krista';
$password = 'krista';
$database = 'restod';

$yhendus = mysqli_connect($host, $user, $password, $database);

if (!$yhendus) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($yhendus, $_POST['username']);
    $password = mysqli_real_escape_string($yhendus, $_POST['password']);

    // Debugging step: print the hashed password
    // echo "MD5 hashed password: " . MD5($password) . "<br>";

    $query = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
    
    // Debugging step: print the query
    // echo "SQL Query: " . $query . "<br>";

    $result = mysqli_query($yhendus, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['authenticated'] = true;
        header("Location: add_edit.php"); // Redirect to add_edit.php on successful login
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

mysqli_close($yhendus);
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
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

<div class="container mt-5">
    <h1>Admin Login</h1>
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Kasutajanimi</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Parool</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Logi sisse</button>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
    </form>
</div>
<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Krista Kutsar ITS-23</span>
    </footer>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Ejt6xkk+VZB+Mvo5M5llgz1TOwCd" crossorigin="anonymous"></script>
</body>
</html>
