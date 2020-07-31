<?php

class db
{
    // Доступы к БД
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'test2';

    public function __construct()
    {
        // Установовим соединение с БД
        try {
            $this->pdo = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=utf8;', $this->db_user, $this->db_pass);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}