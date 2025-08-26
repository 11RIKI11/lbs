<?php

    $title = 'Не авторизован';

?>

<section class="page-box error">
    <h1 class="error-title">Ошибка 401</h1>
    <p class="error-name">Вы не авторизованы</p>
    <p id="error-message">Войдите в систему для просмотра этого ресурса.</p>
    <button type="button" onclick="window.location.href='/'" class="return-to=home">Вернуться на главную</a></button>
    <button type="button" onclick="window.location.href='/auth/login'" class="go-to=login">Авторизация</a></button>
</section>