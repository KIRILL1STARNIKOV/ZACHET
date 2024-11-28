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

// Получаем данные для редактирования
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM stajirovka WHERE id = $id";
    $result = $sql->query($query);
    $internship = $result->fetch_assoc();
}

// Обработка формы редактирования стажировки
if (isset($_POST['edit_internship'])) {
    $name = $sql->real_escape_string($_POST['name']);
    $description = $sql->real_escape_string($_POST['description']);
    $date = $sql->real_escape_string($_POST['date']); // Переименовано с duration на date

    // Обновляем стажировку
    $updateQuery = "UPDATE stajirovka SET name = '$name', description = '$description', date = '$date' WHERE id = $id";
    $sql->query($updateQuery);

    // Перенаправление после обновления
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование стажировки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Редактирование стажировки</h1>

    <form method="POST" action="edit_internship.php?id=<?= $internship['id'] ?>" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($internship['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($internship['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Срок</label> <!-- Переименовано с data на date -->
            <input type="text" name="date" id="date" class="form-control" value="<?= htmlspecialchars($internship['date']) ?>" required>
        </div>

        <button type="submit" name="edit_internship" class="btn btn-primary">Обновить стажировку</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Закрытие соединения
$sql->close();
?>