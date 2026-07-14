<?php
session_start();
include "../db.php";
include "../sms.php";


// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied. Please login.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $user_id  = $_SESSION['user_id'];
    $method   = mysqli_real_escape_string($conn, $_POST['method']);
    $acc_num  = mysqli_real_escape_string($conn, $_POST['acc_number']);
    $trx_id   = mysqli_real_escape_string($conn, $_POST['transaction_id']);

    // 2. IMPORTANT: Verify the order belongs to this user AND is currently 'pending'
    // We use LOWER(status) to handle 'Pending' or 'pending'
    $check_sql = "SELECT id FROM orders WHERE id = $order_id AND customer_id = $user_id AND LOWER(status) = 'pending'";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        // 3. Update the order to 'Paid' and save the bank details
        $update_sql = "UPDATE orders SET 
                        status = 'Paid', 
                        payment_method = '$method', 
                        transaction_id = '$trx_id',
                        acc_number = '$acc_num'
                        WHERE id = $order_id";

        if (mysqli_query($conn, $update_sql)) {
            echo "<script>
                alert('Payment details for Order #$order_id submitted successfully!');
                window.location.href='view_orders.php';
            </script>";
        } else {
            // This happens if the columns don't exist in the DB
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        // This is why you see "Invalid"
        echo "<script>
            alert('Invalid Order: This order might already be paid, delivered, or it does not belong to your account.');
            window.location.href='view_orders.php';
        </script>";
    }
} else {
    header("Location: view_orders.php");
}
?>
