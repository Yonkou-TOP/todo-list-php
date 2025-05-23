<?php
$host = 'localhost:8111'; // tambahkan :4306
$user = 'root';
$pass = ''; // kosongkan jika memang tidak pakai password
$db   = 'todolist_db';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>