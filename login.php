<?php
session_start(); // Стартуємо сесію

// Перевірка, чи форма була надіслана
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php'; // Підключення до бази даних

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Підготовка запиту для перевірки наявності користувача
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Користувач знайдений, перевіряємо пароль
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Пароль вірний, створюємо сесію
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Перенаправлення на головну сторінку
            header("Location: http://127.0.0.1:5500/index.html");
            exit();
        } else {
            // Невірний пароль
            echo "<p>Невірний пароль!</p>";
        }
    } else {
        // Користувач не знайдений
        echo "<p>Користувача не знайдено!</p>";
    }
}
?>
