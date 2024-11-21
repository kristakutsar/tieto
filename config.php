<?php

define('DB_HOST', 'localhost'); // Andmebaasi host
define('DB_USER', 'krista');    // Andmebaasi kasutajanimi
define('DB_PASSWORD', 'krista'); // Andmebaasi parool
define('DB_NAME', 'restod');     // Andmebaasi nimi

$yhendus = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$yhendus) {
    die("Ühendus andmebaasiga ebaõnnestus: " . mysqli_connect_error());
}
?>
