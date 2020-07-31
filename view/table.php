<?php
    if (!empty($view->data))
    {
        echo '<table border="1">';

        foreach ($view->data as $val)
        {
            echo '<tr><td>' . $val[0] . '</td></tr>';
        }

        echo '</table>';

    } else {
        echo 'Ничего не найдено!';
    }
?>
