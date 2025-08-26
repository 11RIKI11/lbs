<?php 
$title = 'Вход в систему';
?>

<h1 class="page-title"><?=$title?></h1>
<section class="page-box form-box" id="login-box">
    <h2>Вход в систему</h2>
    <form method="POST" action="/auth/login" name="login" novalidate>
        <label for="login">Имя пользователя:</label><br>
        <input type="text" 
               id="login" 
               name="login" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? ($formData['login'] ?? '') : '' ?>">
        <?= isset($errorsTags['login']) ? ($errorsTags['login'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="password">Пароль:</label><br>
        <input type="password" 
               id="password" 
               name="password" 
               class="inputUncheck" 
               required
               value="<?= isset($formData) ? ($formData['password'] ?? '') : '' ?>">
        <?= isset($errorsTags['password']) ? ($errorsTags['password'][0] ?? '<br><br>') : '<br><br>'?>

        <?php if (isset($errors['general'])): ?>
            <div class="error-message general-error">
                <?= htmlspecialchars($errors['general'][0]) ?><br><br>
            </div>
        <?php endif; ?>

        <button type="submit">Войти</button>
        <button type="reset">Очистить</button>
    </form>
</section>