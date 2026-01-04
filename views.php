<?php
require_once 'connect.php';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна 7: Views та Транзакції</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #673AB7; color: white; }
        .section { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #fdfdfd; }
    </style>
</head>
<body>

<h1>Лабораторна робота №7</h1>

<div class="section">
    <h2>1. Використання Представлення (VIEW)</h2>
    <p>Ця таблиця побудована на основі віртуальної таблиці <b>OrderDetailsView</b></p>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Клієнт</th>
            <th>Послуга</th>
            <th>Ціна</th>
            <th>Статус</th>
        </tr>
        <?php

        $sql = "SELECT * FROM OrderDetailsView ORDER BY order_id DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['full_name'] . "</td>";
                echo "<td>" . $row['service_name'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</div>
<div class="section">
    <h2>2. Робота з Транзакцією</h2>
    <?php
    $conn->begin_transaction();

    try {
        $conn->query("INSERT INTO Services (service_name, price) VALUES ('Тестова послуга', 100.00)");
        
        $last_id = $conn->insert_id;

        $conn->query("UPDATE Services SET price = 150.00 WHERE service_id = $last_id");

        $conn->commit();
        echo "<p style='color:green'>Транзакція пройшла успішно! Послуга додана і оновлена.</p>";
        
    } catch (mysqli_sql_exception $exception) {

        $conn->rollback();
        echo "<p style='color:red'>Помилка! Транзакцію скасовано. " . $exception->getMessage() . "</p>";
    }
    ?>
</div>
<div class="section">
    <h2>3. Паралельний запит (Імітація навантаження)</h2>
    <p>Цей запит виконується із затримкою у 3 секунди, імітуючи складні обчислення на стороні бази даних.</p>
    
    <?php
    $start_time = microtime(true);

    $sql = "SELECT COUNT(*) as count, SLEEP(3) FROM Clients";
    
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        
        $end_time = microtime(true);
        $duration = round($end_time - $start_time, 2);

        echo "Кількість клієнтів: <b>" . $row['count'] . "</b><br>";
        echo "Час виконання запиту: <b>" . $duration . " сек.</b>";
    }
    ?>

</div>
<div class="section">
    <h2>4. Блокування таблиць (LOCK TABLES)</h2>
    <p>Цей скрипт блокує таблицю <b>Services</b> для запису. Це означає, що тільки поточне з'єднання може читати та змінювати її. Всі інші користувачі будуть чекати.</p>
    
    <?php
    try {
        $conn->query("LOCK TABLES Services WRITE");
        echo "<p>🔴 <b>Статус:</b> Таблиця 'Services' ЗАБЛОКОВАНА (WRITE LOCK).</p>";

        $res = $conn->query("SELECT COUNT(*) as cnt FROM Services");
        $row = $res->fetch_assoc();
        echo "<p>Виконуємо захищену операцію... У базі зараз {$row['cnt']} послуг.</p>";

        //sleep(5); 

    } catch (Exception $e) {
        echo "Помилка: " . $e->getMessage();
    } finally {

        $conn->query("UNLOCK TABLES");
        echo "<p>🟢 <b>Статус:</b> Таблиця РОЗБЛОКОВАНА.</p>";
    }
    ?>
</div>

<?php 

$conn->close(); 
?>
</body>
</html>