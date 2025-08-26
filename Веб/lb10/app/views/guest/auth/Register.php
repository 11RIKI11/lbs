<?php 
$title = 'Регистрация';
?>

<h1 class="page-title"><?=$title?></h1>
<section class="page-box form-box" id="register-box">
    <h2>Регистрация</h2>
    <form method="POST" action="/auth/register" name="register" novalidate>
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
