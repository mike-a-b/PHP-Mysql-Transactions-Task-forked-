<?php
include_once('db.php');
include_once('model.php');

$user_id = isset($_POST['user'])
    ? (int)$_POST['user']
    : null;

if ($user_id) {
    $conn = get_connect();
    // Get transactions balances
//    $transactions = get_user_transactions_balances($user_id, $conn);
    // TODO: implement
    // testline
    echo $_POST['user'];
} else {
    echo $_POST['user'];
}
?>