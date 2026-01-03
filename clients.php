<?php
require_once 'connect.php';

if (isset($_POST['add_btn'])) {
    $name = $_POST['full_name'];
    $phone = $_POST['phone_number'];
    
    $sql = "INSERT INTO Clients (full_name, phone_number) VALUES ('$name', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Клієнта успішно додано!'); window.location.href='clients.php';</script>";
    } else {
        echo "Помилка: " . $conn->error;
    }
}

if (isset($_POST['delete_btn'])) {
    $id_to_delete = $_POST['delete_id'];
    
    $sql = "DELETE FROM Clients WHERE client_id = '$id_to_delete'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Клієнта видалено!'); window.location.href='clients.php';</script>";
    } else {
        echo "Помилка видалення: " . $conn->error;
    }
}


if (isset($_POST['edit_last_btn'])) {
    $sql_last = "SELECT client_id FROM Clients ORDER BY client_id DESC LIMIT 1";
    $result_last = $conn->query($sql_last);
    
    if ($row = $result_last->fetch_assoc()) {
        $last_id = $row['client_id'];
        header("Location: edit.php?id=" . $last_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Управління Клієнтами</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { display: flex; gap: 30px; }
        .form-box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #f9f9f9; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        button { cursor: pointer; padding: 5px 10px; }
        .edit-link { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<h1>Управління таблицею "Клієнти"</h1>

<div class="container">
    <div class="form-box">
        <h3>Додати нового клієнта</h3>
        <form method="POST">
            <input type="text" name="full_name" placeholder="ПІБ" required><br><br>
            <input type="text" name="phone_number" placeholder="Телефон" required><br><br>
            <button type="submit" name="add_btn">Додати (INSERT)</button>
        </form>
    </div>

    <div class="form-box">
        <h3>Видалити за ID (Пункт b)</h3>
        <form method="POST">
            <input type="number" name="delete_id" placeholder="Введіть ID" required><br><br>
            <button type="submit" name="delete_btn" style="background-color: #ffcccc;">Видалити (DELETE)</button>
        </form>
    </div>

    <div class="form-box">
        <h3>Редагування (Пункт c)</h3>
        <form method="POST">
            <p>Змінити останній введений запис:</p>
            <button type="submit" name="edit_last_btn">Редагувати останнього</button>
        </form>
    </div>
</div>

<hr>

<h3>Список клієнтів (Перегляд)</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Ім'я</th>
        <th>Телефон</th>
        <th>Дія</th>
    </tr>
    <?php
    $sql = "SELECT * FROM Clients";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['client_id'] . "</td>";
            echo "<td>" . $row['full_name'] . "</td>";
            echo "<td>" . $row['phone_number'] . "</td>";
            echo "<td><a class='edit-link' href='edit.php?id=" . $row['client_id'] . "'>Редагувати</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Немає даних</td></tr>";
    }
    ?>
</table>

</body>
</html>