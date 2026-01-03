<?php

$servername = "localhost";
$username = "root";       
$password = "root";       
$dbname = "photocenter_db"; 
$port = 8889;           

// Створюємо підключення
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Перевіряємо помилки
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

// Вмикаємо українську мову
$conn->set_charset("utf8mb4");
?>