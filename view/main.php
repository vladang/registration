<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Тестовое задание</title>
    <link rel="stylesheet" href="/css/style.css" type="text/css" media="all" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="/js/script.js"></script>
</head>
<body>
    <h1>Тестовое задание</h1>
    <hr/>
    <a href="?r=repeat-email">Повторяющиеся E-mail</a> |
    <a href="?r=no-orders">Не сделали ни одного заказа</a> |
    <a href="?r=two-orders">Сделали более двух заказов</a>
    <hr/>

    <?php
        if ($view->is_auth)
            echo '<b><a href="?r=edit">Редактировать профиль</a> | <a href="?r=logout">Выход</a></b>';
        else
            echo '<b><a href="?r=auth">Авторизация</a> | <a href="?r=reg">Регистрация</a></b>' ;

        if (isset($view->include) && file_exists('view/' . $view->include . '.php'))
            include_once 'view/' . $view->include . '.php';
    ?>

</body>
</html>