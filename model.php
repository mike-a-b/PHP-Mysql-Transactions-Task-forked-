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
    $balance = array();
    try {
        $trans_to = $conn->query('SELECT SUM(amount) AS sum_amount,
                                         strftime("%m", trdate) AS tr_month 
                                FROM `transactions` 
                                WHERE `transactions`.`account_to` = (SELECT DISTINCT `user_accounts`.`id` FROM `user_accounts`
                                                                     WHERE `user_accounts`.`user_id` = :user_id)
                                     AND (`transactions`.`trdate` BETWEEN DATE("now", "-1 year") AND DATE("now"))
                                GROUP BY tr_month');
        $trans_to->bindParam(':user_id', $user_id);
        if($trans_to->execute()) {
            while($row = $trans_to->fetch(PDO::FETCH_ASSOC)){
                $balance[(int)$row['tr_month']] = $row['sum_amount'];
            }
            return $balance;
        } else {
            return null;
        }
    }
    catch (PDOException $e) {
        return [0 => $e->getMessage()];
    }
}