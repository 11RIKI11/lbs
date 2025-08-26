<?php 
$title = 'Регистрация';
?>

<h1 class="page-title"><?=$title?></h1>
<section class="page-box form-box" id="register-box">
    <h2>Регистрация</h2>
    <form method="POST" action="/auth/register" name="register" novalidate onsubmit="return checkLoginNotBusy();">
        <label for="fio">Фамилия Имя Отчество:</label><br>
        <input type="text" 
               id="fio" 
               name="fio" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? htmlspecialchars($formData['fio'] ?? '') : '' ?>">
        <?= isset($errorsTags['fio']) ? ($errorsTags['fio'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="login">Имя пользователя (Логин):</label><br>
        <input type="text" 
               id="login" 
               name="login" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? htmlspecialchars($formData['login'] ?? '') : '' ?>">
        <span id="login-availability-result"></span>
        <?= isset($errorsTags['login']) ? ($errorsTags['login'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="email">Электронная почта:</label><br>
        <input type="email" 
               id="email" 
               name="email" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? htmlspecialchars($formData['email'] ?? '') : '' ?>">
        <?= isset($errorsTags['email']) ? ($errorsTags['email'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="password">Пароль:</label><br>
        <input type="password" 
               id="password" 
               name="password" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? htmlspecialchars($formData['password'] ?? '') : '' ?>">
        <?= isset($errorsTags['password']) ? ($errorsTags['password'][0]?? '<br><br>') : '<br><br>'?>

        <?php if (isset($errors['general'])): ?>
            <div class="error-message general-error">
                <?= htmlspecialchars($errors['general'][0]) ?>
            </div>
        <?php endif; ?>

        <button type="submit">Зарегистрироваться</button>
        <button type="reset">Очистить</button>
    </form>
</section>

<!-- Форма и iframe для проверки логина -->
<form id="check-login-form" method="POST" action="/auth/check-login" target="check-login-iframe" style="display:none;">
    <input type="hidden" name="login" id="check-login-hidden">
</form>
<iframe name="check-login-iframe" id="check-login-iframe" style="display:none;"></iframe>

<script>
var loginBusy = false;

document.getElementById('login').addEventListener('blur', function() {
    var loginValue = document.getElementById('login').value;
    document.getElementById('check-login-hidden').value = loginValue;
    document.getElementById('check-login-form').submit();
});

// Обработка ответа из iframe
document.getElementById('check-login-iframe').addEventListener('load', function() {
    try {
        var iframe = document.getElementById('check-login-iframe');
        var doc = iframe.contentDocument || iframe.contentWindow.document;
        var responseText = doc.body.textContent || doc.body.innerText;
        var result = JSON.parse(responseText);
        var resultSpan = document.getElementById('login-availability-result');
        var errorTag = document.querySelector('[data-login-error]');
        // Если логин занят, показываем только это сообщение и убираем остальные ошибки
        if (result && result.available === false && result.message === 'Логин уже занят') {
            resultSpan.textContent = result.message;
            resultSpan.className = 'error-message';
            if (errorTag) errorTag.innerHTML = '';
            loginBusy = true;
        } else {
            resultSpan.textContent = '';
            resultSpan.className = '';
            loginBusy = false;
            // Восстанавливаем ошибки, если нужно (на сервере они появятся при отправке формы)
        }
    } catch (e) {
        // Ошибка парсинга или доступа к iframe
        loginBusy = false;
    }
});

function checkLoginNotBusy() {
    if (loginBusy) {
        alert('Вы не можете отправить форму: логин уже занят.');
        return false;
    }
    return true;
}
</script>
