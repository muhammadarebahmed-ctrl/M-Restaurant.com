<?php
session_start();
include "../db.php";
include "../sms.php";


if (!isset($_GET['order_id'])) { header("Location: view_orders.php"); exit(); }

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Check if order exists and belongs to user and is still PENDING
$check_sql = "SELECT item_id FROM orders WHERE id = ? AND customer_id = ? AND status = 'pending'";
$stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$current_order = mysqli_fetch_assoc($res);

if (!$current_order) {
    echo "<script>alert('You cannot edit this order.'); window.location='view_orders.php';</script>";
    exit();
}

// Get all menu items for the dropdown
$menu_result = mysqli_query($conn, "SELECT * FROM menu_items");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order - M-Restaurant</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .edit-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 400px; }
        select, button { width: 100%; padding: 12px; margin-top: 15px; border-radius: 8px; border: 1px solid #ddd; }
        button { background: #2e7d32; color: white; border: none; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="edit-card">
    <h2>Edit Order #<?= $order_id ?></h2>
    <p>Change your meal selection below:</p>
    
    <form action="update_order_process.php" method="POST">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        
        <label>Select New Food Item:</label>
        <select name="new_item_id" required>
            <?php while($item = mysqli_fetch_assoc($menu_result)): ?>
                <option value="<?= $item['id'] ?>" <?= ($item['id'] == $current_order['item_id']) ? 'selected' : '' ?>>
                    <?= $item['name'] ?> - (<?= $item['price'] ?> Birr)
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">UPDATE ORDER</button>
        <a href="view_orders.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
    </form>
</div>

</body>
</html>