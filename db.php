<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'todolist_db';
$port = 8111;

$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Gagal koneksi ke MySQL: " . $mysqli->connect_error);
}
