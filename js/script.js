function getVal(name)
{
    return $('input[name="' + name + '"]').val();
}

function parseJson(data, id)
{
    let jdata = JSON.parse(data);

    if (jdata.status == 'success') {
        $('#' + id).text(jdata.message);
        return true;
    } else {
        alert(jdata.message);
        return false;
    }
}

$(function()
{
    // Регистрация
    $('#reg-form').submit(function() {
        $.post('?r=reg', {
                login: getVal('login'), email: getVal('email'), fio: getVal('fio'), password: getVal('password')
            },
            function(data) {
                parseJson(data, 'reg-form');
            }
        );
        return false;
    });

    // Редактирование
    $('#edit-form').submit(function() {
        $.post('?r=edit', {
                email: getVal('email'), fio: getVal('fio'), password: getVal('password')
            },
            function(data) {
                parseJson(data, 'edit-form');
            }
        );
        return false;
    });

    // Авторизация
    $('#auth-form').submit(function() {
        $.post('?r=auth', {
                login: getVal('login'), password: getVal('password')
            },
            function(data) {
                if (parseJson(data, 'auth-form')) document.location.href = '?';
            }
        );
        return false;
    });
});