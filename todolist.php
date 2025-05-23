<?php
$tasks = [];

function tambahTugas(&$tasks, $judul) {
    $id = count($tasks) + 1;
    $tasks[] = ['id' => $id, 'title' => $judul, 'status' => 'Belum'];
}

function tampilkanTugas($tasks) {
    echo "<h2>Daftar Tugas</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Judul</th><th>Status</th></tr>";
    foreach ($tasks as $task) {
        echo "<tr>
                <td>{$task['id']}</td>
                <td>{$task['title']}</td>
                <td>{$task['status']}</td>
              </tr>";
    }
    echo "</table>";
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    tambahTugas($tasks, $judul);
}
?>

<!-- Form Tambah Tugas -->
<h2>Tambah Tugas</h2>
<form method="post">
    <label for="judul">Judul Tugas:</label>
    <input type="text" name="judul" id="judul" required>
    <button type="submit">Tambah</button>
</form>

<?php
// Tampilkan daftar tugas setelah form
tampilkanTugas($tasks);
?>
