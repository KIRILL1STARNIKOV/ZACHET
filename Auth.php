<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Настройки подключения к базе данных
    $sql = new mysqli('127.0.0.1', 'root', '', 'Zachet'); // Замените на название вашей базы данных

    if ($sql->connect_error) {
        die("Ошибка подключения: " . $sql->connect_error);
    }

    $login = trim($_POST['login']);
    $password = trim($_POST['pass']);

    if (empty($login) || empty($password)) {
        echo "Пожалуйста, заполните все поля.";
    } else {
        $stmt = $sql->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $storedPassword);
            $stmt->fetch();

            // Проверка пароля
            if ($password === $storedPassword) {
                // Сохранение данных сессии
                $_SESSION['id'] = $user_id;
                $_SESSION['username'] = $login;

                // Редирект на index.php
                header("Location: index.php");
                exit; // Завершаем выполнение скрипта, чтобы не было дальнейшего вывода
            } else {
                echo "Неверный пароль. Попробуйте снова.";
            }
        } else {
            echo "Пользователь с таким логином не найден.";
        }
        $stmt->close();
    }
    $sql->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <title>Форма авторизации</title>
  <style>
    body { background-color: #f8f9fa; }
    .form-container { margin-top: 50px; }
    .form-container h1 { margin-bottom: 30px; text-align: center; }
    .form-container .btn { width: 100%; }
    .form-group { margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="container form-container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h1>Форма авторизации</h1>
        <form action="auth.php" method="post">
          <div class="form-group">
            <input type="text" name="login" class="form-control" placeholder="Логин" required>
          </div>
          <div class="form-group">
            <input type="password" name="pass" class="form-control" placeholder="Пароль" required>
          </div>
          <button class="btn btn-success">Авторизоваться</button>
        </form>
        <br>
      </div>
    </div>
  </div>
</body>
</html>