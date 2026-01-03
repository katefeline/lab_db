<?php
// Підключаємо файл з налаштуваннями
require_once 'connect.php';

// SQL-запит: вибираємо деталі замовлень
$sql = "SELECT 
            Orders.order_id, 
            Clients.full_name, 
            Clients.phone_number,
            Services.service_name, 
            Services.price, 
            Orders.status,
            Orders.order_date
        FROM Orders
        JOIN Clients ON Orders.client_id = Clients.client_id
        JOIN Services ON Orders.service_id = Services.service_id
        ORDER BY Orders.order_date ASC";

// Виконуємо запит
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>АІС Фотоцентру - Замовлення</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #ddd; }
        .status-new { color: blue; font-weight: bold; }
        .status-ready { color: green; font-weight: bold; }
        .status-processing { color: orange; font-weight: bold; }
    </style>
</head>
<body>

    <h1>📋 Список замовлень Фотоцентру</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клієнт</th>
                    <th>Телефон</th>
                    <th>Послуга</th>
                    <th>Ціна (грн)</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['service_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td class="status-<?php echo $row['status']; ?>">
                            <?php echo $row['status']; ?>
                        </td>
                        <td><?php echo $row['order_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У базі даних поки немає замовлень.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>

</body>
</html>