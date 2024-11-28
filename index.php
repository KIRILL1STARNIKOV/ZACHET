<?php
session_start();

$host = '127.0.0.1';
$dbname = 'Zachet'; // Замените на название вашей базы данных
$user = 'root';
$password = ''; // Если есть пароль для MySQL, укажите его

// Подключение к базе данных через mysqli
$sql = new mysqli($host, $user, $password, $dbname);

// Проверка соединения
if ($sql->connect_error) {
    die("Ошибка подключения: " . $sql->connect_error);
}

// Обработка формы добавления стажировки
if (isset($_POST['add_internship'])) {
    $name = $sql->real_escape_string($_POST['name']);
    $description = $sql->real_escape_string($_POST['description']);
    $duration = date('Y-m-d', strtotime($sql->real_escape_string($_POST['date']))); // Преобразуем дату
    
    $insertQuery = "INSERT INTO stajirovka (name, description, date) VALUES ('$name', '$description', '$duration')";
    $sql->query($insertQuery);

    // Перенаправление после добавления
    header("Location: index.php");
    exit;
}

// Получаем все стажировки
$query = "SELECT * FROM stajirovka";
$result = $sql->query($query);

// Удаление стажировки
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $deleteQuery = "DELETE FROM stajirovka WHERE id = $id";
    $sql->query($deleteQuery);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Стажировки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Стажировки</h1>
    <div class="text-center mb-4">
        <img src="img/1.jpg" alt="Логотип" style="width: 200px;">
    </div>
    <div class="text-end mb-4">
        <a href="Auth.php" class="btn btn-danger">Выйти</a>
    </div>

    <!-- Форма для добавления стажировки -->
    <form method="POST" action="index.php" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="data" class="form-label">Срок</label>
            <input type="text" name="data" id="data" class="form-control" required>
        </div>

        <button type="submit" name="add_internship" class="btn btn-primary">Добавить стажировку</button>
    </form>

    <!-- Таблица стажировок -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Срок</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($internship = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($internship['id']) ?></td>
                <td><?= htmlspecialchars($internship['name']) ?></td>
                <td><?= htmlspecialchars($internship['description']) ?></td>
                <td><?= htmlspecialchars($internship['date']) ?></td>
                <td>
                    <a href="edit_internship.php?id=<?= $internship['id'] ?>" class="btn btn-warning btn-sm">Редактировать</a>
                    <a href="?delete=<?= $internship['id'] ?>" class="btn btn-danger btn-sm">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Закрытие соединения
$sql->close();
?>