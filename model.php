<?php

/**
 * Return list of users.
 * @param $conn
 * @return array
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
 * @return void
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
 * Return transactions balances of given user.
 * @param $user_id
 * @param $conn
 * @return array
 */
function get_user_transactions_balances($user_id, $conn)
{     
    // TODO: implement
    return [];
}