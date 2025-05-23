<?php
include "config.php";
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        ['task' => 'Mengerjakan tugas matematika', 'done' => false],
        ['task' => 'Membaca buku PHP', 'done' => false],
        ['task' => 'Berolahraga selama 30 menit', 'done' => true],
    ];
}

function tambahTugas(string $newTask, $conn): void {
    $task = trim($newTask);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (task, done) VALUES (?, 0)");
        $stmt->bind_param("s", $task);
        $stmt->execute();
        $stmt->close();
    }
}
function getTasks($conn): array {
    $result = $conn->query("SELECT id, task, done FROM tasks");
    $tasks = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }
    return $tasks;
}


function hapusTugas(int $index): void {
    if (isset($_SESSION['tasks'][$index])) {
        array_splice($_SESSION['tasks'], $index, 1);
    }
}

function updateStatus(int $index, bool $done): void {
    if (isset($_SESSION['tasks'][$index])) {
        $_SESSION['tasks'][$index]['done'] = $done;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'add' && isset($_POST['task'])) {
            tambahTugas($_POST['task']);
        } elseif ($action === 'delete' && isset($_POST['index'])) {
            hapusTugas((int)$_POST['index']);
        } elseif ($action === 'toggle' && isset($_POST['index']) && isset($_POST['done'])) {
            updateStatus((int)$_POST['index'], $_POST['done'] === '1');
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$tasks = $_SESSION['tasks'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aplikasi ToDo List - Tambah, Selesai & Hapus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 60px;
        }
        h1 {
            color: #0d6efd;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(13, 110, 253, 0.3);
        }
        .task-done {
            text-decoration: line-through;
            color: #6c757d !important;
            transition: color 0.3s ease;
        }
        .btn-danger {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }
        .form-control:focus {
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
            border-color: #0d6efd;
        }
        .list-group-item {
            transition: background-color 0.3s ease;
        }
        .list-group-item:hover {
            background-color: #f1f5fb;
        }
        .form-check-input {
            cursor: pointer;
            width: 1.3em;
            height: 1.3em;
        }
        .form-check-label {
            cursor: pointer;
            user-select: none;
            font-size: 1.1rem;
        }
        .empty-message {
            font-style: italic;
            color: #6c757d;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container bg-white p-4 rounded shadow-sm">
    <h1 class="mb-4 text-center">Aplikasi ToDo List</h1>

    <!-- Form tambah tugas -->
    <form method="POST" class="d-flex mb-4" aria-label="Form tambah tugas">
        <input type="hidden" name="action" value="add" />
        <input
            type="text"
            name="task"
            class="form-control me-2"
            placeholder="Masukkan tugas baru..."
            aria-label="Masukkan tugas baru"
            required
            autofocus
        />
        <button type="submit" class="btn btn-primary px-4">Tambah</button>
    </form>

    <!-- Daftar tugas -->
    <?php if (count($tasks) === 0) : ?>
        <p class="empty-message">Belum ada tugas. Tambahkan tugas baru!</p>
    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($tasks as $index => $taskItem) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="form-check m-0">
                        <form method="POST" class="d-inline" style="display:inline;" aria-label="Checkbox tugas <?php echo htmlspecialchars($taskItem['task']); ?>">
                            <input type="hidden" name="action" value="toggle" />
                            <input type="hidden" name="index" value="<?php echo $index; ?>" />
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="taskCheck<?php echo $index; ?>"
                                name="done"
                                value="1"
                                onchange="this.form.submit()"
                                <?php echo $taskItem['done'] ? 'checked' : ''; ?>
                            />
                            <label
                                class="form-check-label <?php echo $taskItem['done'] ? 'task-done' : ''; ?>"
                                for="taskCheck<?php echo $index; ?>"
                            >
                                <?php echo htmlspecialchars($taskItem['task']); ?>
                            </label>
                        </form>
                    </div>
                    <form method="POST" aria-label="Hapus tugas <?php echo htmlspecialchars($taskItem['task']); ?>">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="index" value="<?php echo $index; ?>" />
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus tugas" onclick="return confirm('Hapus tugas ini?')">
                            &times;
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
