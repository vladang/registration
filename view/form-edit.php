<form name="reg" action="?r=reg" method="post" id="edit-form">
    <label>
        E-Mail:
        <input name="email" type="text" value="<?php echo $view->data->email; ?>">
    </label>
    <label>
        ФИО:
        <input name="fio" type="text" value="<?php echo $view->data->fio; ?>">
    </label>
    <label>
        Пароль:
        <input name="password" type="password" value="">
    </label>
    <label>
        <input type="submit" value="Сохранить изменения">
    </label>
</form>