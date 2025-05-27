<?php
ob_start(); 
ini_set('session.cookie_path', '/');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('DB_TYPE', 'mysql');
define('DB_HOST', 'db');
define('DB_PORT', '3306');


define('DB_NAME', 'TestDB');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//define some constants:
define('ROOT_PATH', realpath(dirname(__FILE__)));
define('BASE_URL', 'http://localhost:8080/');

ob_end_flush(); // tout à la toute fin du fichier
?>