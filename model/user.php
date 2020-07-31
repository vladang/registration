<?php

class user extends db
{
    public function __construct()
    {
        parent::__construct();

        $this->session = (object)$_SESSION;
    }

    /*
        Проверить логин на занятость
    */
    private function checkLogin($login)
    {
        $user = $this->pdo->prepare('SELECT 1 FROM users WHERE login = :login');
        $user->execute(['login' => $login]);

        return $user->fetchColumn();
    }

    /*
        Проверить авторизован ли пользователь
    */
    public function checkAuth()
    {
        if (empty($this->session->id_user) || empty($this->session->hash))
            return false;

        $user = $this->pdo->prepare('SELECT 1 FROM users WHERE id = :id AND password = :password');
        $user->execute([
            'id' => $this->session->id_user,
            'password' => $this->session->hash
        ]);

        return $user->fetchColumn();
    }

    /*
        Проверить валидность Email (если он указан)
    */
    public function checkEmail($email)
    {
        if (!empty($email) && !preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $email))
            return false;
        else
            return true;
    }

    /*
        Регистрация пользователя
    */
    public function registration($post)
    {
        $error = [];
        if (empty($post->login)) $error[] = 'Логин обязателен для заполнения';
        if (empty($post->password)) $error[] = 'Пароль обязателен для заполнения';
        if (!$this->checkEmail($post->email)) $error[] = 'Email введен не корректно';
        if (!$error && $this->checkLogin($post->login)) $error[] = 'Данный логин занят';

        if ($error) {
            return json_encode(['status' => 'error', 'message' => implode('; ', $error)]);
        } else {
            $user = $this->pdo->prepare('INSERT INTO users (email, login, password, fio) VALUES (:email, :login, :password, :fio)');
            $user->execute([
                ':email' => $post->email,
                ':login' => $post->login,
                ':password' => md5($post->password),
                ':fio' => $post->fio,
            ]);
            return json_encode(['status' => 'success', 'message' => 'Регистрация успешно завершена, вы можете войти']);
        }
    }

    /*
        Редактирование пользователя
    */
    public function editUser($post)
    {
        if (!$this->checkAuth()) return false;

        if (!$this->checkEmail($post->email)) {
            return json_encode(['status' => 'error', 'message' => 'Email введен не корректно']);
        } else {
            $password = empty($post->password) ? $this->session->hash : md5($post->password);
            $user = $this->pdo->prepare('UPDATE users SET email = :email, password = :password, fio = :fio WHERE id = :id_user');
            $user->execute([
                ':email' => $post->email,
                ':password' => $password,
                ':fio' => $post->fio,
                ':id_user' => $this->session->id_user
            ]);
            if ($this->session->hash != $password) $_SESSION['hash'] = $password;

            return json_encode(['status' => 'success', 'message' => 'Профиль успешно отредактирован!']);
        }
    }

    /*
        Авторизация пользователя
    */
    public function authorization($post)
    {
        $error = [];
        if (empty($post->login)) $error[] = 'Логин не указан';
        if (empty($post->password)) $error[] = 'Пароль не указан';
        if (!$error) {
            $user = $this->pdo->prepare('SELECT id FROM users WHERE login = :login AND password = :password');
            $user->execute([
                'login' => $post->login,
                'password' => md5($post->password)
            ]);
            if ($id_user = $user->fetchColumn()) {
                $_SESSION['id_user'] = $id_user;
                $_SESSION['hash'] = md5($post->password);
                return json_encode(['status' => 'success', 'message' => 'Авторизация успешна']);
            } else {
                $error[] = 'Не верный логин или пароль';
            }
        }
        return json_encode(['status' => 'error', 'message' => implode('; ', $error)]);
    }

    /*
        Возвратить данные пользователя
    */
    public function getUserInfo()
    {
        if (!$this->checkAuth()) return false;

        $user = $this->pdo->prepare('SELECT email, login, fio FROM users WHERE id = :id_user');
        $user->execute(['id_user' => $this->session->id_user]);

        return (object)$user->fetch(PDO::FETCH_ASSOC);
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
    }
}