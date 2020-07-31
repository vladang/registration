<?php

class orders extends db
{
    /*
        Выведет список email'лов встречающихся более чем у одного пользователя
    */
    public function getRepeatEmail()
    {
        $email = $this->pdo->prepare('SELECT email, count(*) FROM users WHERE email != "" GROUP BY email HAVING count(*) > 1');
        $email->execute();

        return $email->fetchALL();
    }

    /*
        Вывести список логинов пользователей, которые не сделали ни одного заказа
    */
    public function getLoginNotOrders()
    {
        $login = $this->pdo->prepare('SELECT u.login FROM users u LEFT JOIN orders o ON o.user_id = u.id WHERE o.id IS NULL');
        $login->execute();

        return $login->fetchALL();
    }

    /*
        Вывести список логинов пользователей которые сделали более двух заказов
    */
    public function getLoginMoreTwoOrders()
    {
        $login = $this->pdo->prepare('
            SELECT u.login, count(*) AS count_orders FROM users u
            RIGHT JOIN orders o ON o.user_id = u.id
            GROUP BY 1 HAVING count_orders > 2
        ');
        $login->execute();

        return $login->fetchALL();
    }
}