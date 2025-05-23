<?php
require_once 'db.php';

function getTasks() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM tasks ORDER BY id DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function tambahTugas(string $newTask) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO tasks (task, done) VALUES (?, 0)");
    $stmt->bind_param('s', $newTask);
    $stmt->execute();
    $stmt->close();
}

function hapusTugas(int $id) {
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function updateStatus(int $id, bool $done) {
    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE tasks SET done = ? WHERE id = ?");
    $doneVal = $done ? 1 : 0;
    $stmt->bind_param('ii', $doneVal, $id);
    $stmt->execute();
    $stmt->close();
}
