<?php
$title = 'Редактирование пользователя';
$_POST['id'] = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Получаем все роли и статусы для выпадающих списков
$roles = [];
foreach (UserRole::findAllAssoc() as $role) {
    $roles[$role['id']] = $role['name'];
}
$statuses = [];
foreach (UserStatus::findAllAssoc() as $status) {
    $statuses[$status['id']] = $status['name'];
}

// Определяем роль текущего пользователя
$currentUserRole = $_SESSION['user']['role'] ?? null;
$isSuperAdmin = ($currentUserRole === 'superadmin');

// Используем значения из $user только если $user определён
$fioValue = isset($user) ? htmlspecialchars($user->fio) : '';
$loginValue = isset($user) ? htmlspecialchars($user->login) : '';
$emailValue = isset($user) ? htmlspecialchars($user->email) : '';
$roleIdValue = isset($user) ? (int)$user->role_id : '';
$statusIdValue = isset($user) ? (int)$user->status_id : '';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>

<?php if (empty($user)): ?>
    <div class="page-box">
        <p>Пользователь не найден.</p>
    </div>
<?php else: ?>
    <section class="page-box user-editor-box" style="max-width: 600px; width: 100%;">
        <form method="post" action="/admin/users/edit" class="user-editor-form" id="user-edit-form">
            <input type="hidden" name="id" value="<?= (int)$user->id ?>">
            <div class="form-group">
                <label for="fio">ФИО:</label>
                <input type="text" id="fio" name="fio" value="<?= $fioValue ?>" class="inputUncheck" required>
            </div>
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" value="<?= $loginValue ?>" class="inputUncheck" required disabled>
                <input type="hidden" name="login" value="<?= $loginValue ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $emailValue ?>" class="inputUncheck" required>
            </div>
            <div class="form-group">
                <label for="role_id">Роль:</label>
                <select id="role_id" name="role_id" class="inputUncheck">
                    <?php foreach ($roles as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $roleIdValue == $id ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status_id">Статус:</label>
                <select id="status_id" name="status_id" class="inputUncheck">
                    <?php foreach ($statuses as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $statusIdValue == $id ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Новый пароль:</label>
                <input type="password" id="password" name="password" class="inputUncheck" placeholder="Оставьте пустым, чтобы не менять">
            </div>
            <div class="form-actions">
                <button type="submit">Сохранить</button>
                <button type="reset" name="resetButton" id="resetButton" style="background-color: #f0f0f0; color: #333;">Сбросить</button>
                <button type="button" onclick="window.location.href='/admin/users'">Назад к списку</button>
            </div>
        </form>
        <script>
            // Сброс формы к изначальным значениям
            document.getElementById('resetButton').addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.getElementById('user-edit-form');
                form.reset();
                form.fio.value = "<?= $fioValue ?>";
                form.email.value = "<?= $emailValue ?>";
                form.role_id.value = "<?= $roleIdValue ?>";
                form.status_id.value = "<?= $statusIdValue ?>";
                form.password.value = "";
            });
        </script>
    </section>
    <style>
        .user-editor-box {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }
        .user-editor-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .user-editor-form .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .user-editor-form label {
            font-weight: bold;
            color: #333;
        }
        .user-editor-form .inputUncheck {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .user-editor-form .inputUncheck:focus {
            border-color: #5882eb;
            outline: none;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .form-actions button[type="submit"] {
            padding: 10px 20px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #5882eb;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .form-actions button[type="submit"]:hover {
            opacity: 0.9;
            background-color: #4666b0;
        }
        .user-action-link.user-edit-link {
            padding: 10px 20px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #5882eb;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            display: inline-block;
        }
        .user-action-link.user-edit-link:hover {
            background: #5882eb;
            color: #fff;
        }
        select[disabled] {
            background: #f5f5f5;
            color: light-dark(rgb(84, 84, 84), rgb(170, 170, 170));;
            cursor: not-allowed;
        }
    </style>
<?php endif; ?>
            transition: background 0.2s;
        }
        .form-actions button[type="submit"]:hover {
            opacity: 0.9;
            background-color: #4666b0;
        }
        .user-action-link.user-edit-link {
            padding: 10px 20px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #5882eb;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            display: inline-block;
        }
        .user-action-link.user-edit-link:hover {
            background: #5882eb;
            color: #fff;
        }
        select[disabled] {
            background: #f5f5f5;
            color: light-dark(rgb(84, 84, 84), rgb(170, 170, 170));;
            cursor: not-allowed;
        }
    </style>
