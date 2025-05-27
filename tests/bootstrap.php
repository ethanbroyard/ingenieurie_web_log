<?php
$_ENV['DB_NAME'] = getenv('DB_NAME') ?: 'weblog_test';
$_ENV['DB_USER'] = getenv('DB_USER') ?: 'root';
$_ENV['DB_PASS'] = getenv('DB_PASS') ?: 'root';
$_ENV['DB_HOST'] = getenv('DB_HOST') ?: 'localhost';

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données test : " . $conn->connect_error);
}

// Inclure tes fonctions globales (ex : all_functions.php)
require_once __DIR__ . '/../includes/all_functions.php';
