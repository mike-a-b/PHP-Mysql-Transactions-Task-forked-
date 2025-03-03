<?php
include_once('db.php');
include_once('model.php');
$conn = get_connect_mysql();
$user_id = isset($_POST['user'])
    ? (int)$_POST['user']
    : null;

if ($user_id) {
    // Get transactions balances
    $balances = get_user_transactions_balances($user_id, $conn);
//    $name = get_username_by_id($user_id, $conn);
    if(isset($balances)) echo json_encode($balances);
//    echo ($name ? json_encode($name) : null);
} else {
    echo 0;
}
?>