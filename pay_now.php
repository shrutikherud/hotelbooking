<?php 

require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (isset($_POST['pay_now'])) {
    // Insert payment data into database
    $frm_data = filteration($_POST);

    // Generate order ID with random integer at the end
    $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111, 99999);

    // Calculate total amount (you can modify this based on your business logic)
    $TXN_AMOUNT = $_SESSION['room']['payment'];

    // Insert booking data into database
    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`) VALUES (?, ?, ?, ?, ?)";
    insert($query1, [$_SESSION['uId'], $_SESSION['room']['id'], $frm_data['checkin'], $frm_data['checkout'], $ORDER_ID], 'issss');
    
    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phonenum'], $frm_data['address']], 'issssss');

    // Redirect to payment success page
    redirect('pay_status.php?order=' . $order_id);
}

?>

<html>
<head>
    <title>Processing</title>
</head>
<body>

<h1>Please do not refresh this page...</h1>


</body>
</html>
