<?php
require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql_get = "SELECT * FROM Clients WHERE client_id = '$id'";
    $result = $conn->query($sql_get);
    $client = $result->fetch_assoc();
}

if (isset($_POST['update_btn'])) {
    $id = $_POST['client_id'];
    $name = $_POST['full_name'];
    $phone = $_POST['phone_number'];

    $sql_update = "UPDATE Clients SET full_name='$name', phone_number='$phone' WHERE client_id='$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Дані оновлено!'); window.location.href='clients.php';</script>";
    } else {
        echo "Помилка оновлення: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редагування клієнта</title>
    <style>body { font-family: sans-serif; padding: 20px; }</style>
</head>
<body>

<h2>Редагування запису (Пункт a)</h2>

<?php if ($client): ?>
    <form method="POST">
        <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
        
        <label>Ім'я:</label><br>
        <input type="text" name="full_name" value="<?php echo $client['full_name']; ?>"><br><br>
        
        <label>Телефон:</label><br>
        <input type="text" name="phone_number" value="<?php echo $client['phone_number']; ?>"><br><br>
        
        <button type="submit" name="update_btn">Зберегти зміни (UPDATE)</button>
    </form>
<?php else: ?>
    <p>Клієнта не знайдено!</p>
<?php endif; ?>

<br>
<a href="clients.php">← Повернутися назад</a>

</body>
</html>