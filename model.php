<?php

/**
 * Return list of users.
 * @param $conn
 * @return array all users
 */
function get_users($conn)
{
    try {
        $statement = $conn->query("SELECT * FROM `users`");
        $users = array();
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            $users[$row['id']] = $row['name'];
        }
    }
    catch (PDOException $e) {
        die($e->getMessage());
    }

    return $users;
}

/**
 * Get users with one or more transactions
 * @param $conn
 * @return array users with tr
 */
function get_users_with_transactions($conn) {
    $statement = $conn->query('SELECT * FROM `users` WHERE `users`.`id` in 
                                (SELECT `user_accounts`.`user_id` FROM `user_accounts`
                                WHERE EXISTS (SELECT `transactions`.`id` FROM `transactions`
                                WHERE `transactions`.`account_from` = `user_accounts`.`id` OR `transactions`.`account_to` = `user_accounts`.`id`))');
    $users = array();
    while ($row = $statement->fetch()) {
        $users[$row['id']] = $row['name'];
    }
    return $users;
}

/**
 * @param $conn
 * @param $id
 * @return string
 */
function get_username_by_id($id, $conn) {
    $statement = $conn->prepare('SELECT DISTINCT name FROM `users` WHERE `users`.`id` = :id');
    $statement->bindParam(':id', (int)$id);
    $statement->execute();
    if($row = $statement->fetch(PDO::FETCH_ASSOC)){
        return $row['name'];
    }
}
/**
 * Return transactions balances of given user.
 * @param $user_id
 * @param $conn
 * @return array balances of month transactions
 */
function get_user_transactions_balances($user_id, $conn)
{
    // todo: response error in sql response e-> message
    $balance_to = array();
    $balance_from = array();
    $difference = array();
    try {
        $trans_to = $conn->prepare('SELECT DATE_FORMAT(trdate, "%m") AS tr_month,
                                            SUM(amount) AS sum_amount
                                    FROM transactions
                                    WHERE (transactions.account_to IN (SELECT id 
                                                                       FROM user_accounts 
                                                                       WHERE user_accounts.user_id = :user_id))
                                    GROUP BY tr_month;');
        $trans_to->bindParam(':user_id', $user_id);
        if($trans_to->execute()) {
            while($row = $trans_to->fetch(PDO::FETCH_ASSOC)){

                $balance_to[(int)$row['tr_month']] = $row['sum_amount'];
            }
//            return $balance_to;
        } else {
            return null;
        }
        $trans_from = $conn->prepare('SELECT DATE_FORMAT(trdate, "%m") AS tr_month,
                                            SUM(amount) AS sum_amount
                                    FROM transactions
                                    WHERE (transactions.account_from IN (SELECT id 
                                                                       FROM user_accounts 
                                                                       WHERE user_accounts.user_id = :user_id))
                                    GROUP BY tr_month;');
        $trans_from->bindParam(':user_id', $user_id);
        if($trans_from->execute()) {
            while($row = $trans_from->fetch(PDO::FETCH_ASSOC)){
                $balance_from[(int)$row['tr_month']] = $row['sum_amount'];
                $difference[(int)$row['tr_month']] = $balance_to[(int)$row['tr_month']] - $balance_from[(int)$row['tr_month']];
            }
            return $difference;
        } else {
            return null;
        }
    }
    catch (PDOException $e) {
        return [0 => $e->getMessage()];
    }
}