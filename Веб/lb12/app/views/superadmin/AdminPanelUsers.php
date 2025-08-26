<?php
$title = 'Пользователи';

// Получаем все роли и статусы для сопоставления id => name
$roles = [];
foreach (UserRole::findAllAssoc() as $role) {
    $roles[$role['id']] = $role['name'];
}
$statuses = [];
foreach (UserStatus::findAllAssoc() as $status) {
    $statuses[$status['id']] = $status['name'];
}
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>

<?php if (empty($users)): ?>
    <div class="page-box">
        <p>Нет пользователей.</p>
    </div>
<?php else: ?>
    <section class="page-box">
        <table class="table-box">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Статус</th>
                    <th>Дата создания</th>
                    <th>Дата обновления</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentUserRole = $_SESSION['user']['role'] ?? null;
                $isSuperAdmin = ($currentUserRole === 'superadmin');
                foreach ($users as $user):
                    $roleName = $roles[$user['role_id']] ?? $user['role_id'];
                    $statusName = $statuses[$user['status_id']] ?? $user['status_id'];
                    $isAdminOrSuperadmin = ($roleName === 'admin' || $roleName === 'superadmin');
                ?>
                    <tr>
                        <td><?= (int)$user['id'] ?></td>
                        <td><?= htmlspecialchars($user['fio']) ?></td>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php
                                if ($roleName === 'superadmin') {
                                    echo '<span class="user-role-superadmin">' . htmlspecialchars($roleName) . '</span>';
                                } elseif ($roleName === 'admin') {
                                    echo '<span class="user-role-admin">' . htmlspecialchars($roleName) . '</span>';
                                } elseif ($roleName === 'user') {
                                    echo '<span class="user-role-user">' . htmlspecialchars($roleName) . '</span>';
                                } else {
                                    echo htmlspecialchars($roleName);
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ($statusName === 'blocked') {
                                    echo '<span class="user-status-blocked">' . htmlspecialchars($statusName) . '</span>';
                                } elseif ($statusName === 'active') {
                                    echo '<span class="user-status-active">' . htmlspecialchars($statusName) . '</span>';
                                } else {
                                    echo htmlspecialchars($statusName);
                                }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <?php if (!empty($user['updated_at'])): ?>
                                <?= htmlspecialchars($user['updated_at']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/users/edit?id=<?= (int)$user['id'] ?>" class="user-action-link user-edit-link"
                                <?php if (!$isSuperAdmin && $isAdminOrSuperadmin): ?>style="pointer-events:none;opacity:0.5;cursor:not-allowed;" title="Нет прав на редактирование"<?php endif; ?>
                            >Редактировать</a>
                            <a href="/admin/users/delete?id=<?= (int)$user['id'] ?>" class="user-action-link user-delete-link"
                                <?php if (!$isSuperAdmin && $isAdminOrSuperadmin): ?>style="pointer-events:none;opacity:0.5;cursor:not-allowed;" title="Нет прав на удаление"<?php endif; ?>
                            >Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <style>
            .user-status-blocked {
                background: #faeaea;
                color: #d32f2f;
                border-radius: 5px;
                padding: 4px 12px;
                font-weight: bold;
                display: inline-block;
            }
            .user-status-active {
                background: #e6faed;
                color: #1a7f37;
                border-radius: 5px;
                padding: 4px 12px;
                font-weight: bold;
                display: inline-block;
            }
            .user-role-superadmin {
                background: #e3e3fa;
                color: #4b3fd6;
                border-radius: 5px;
                padding: 4px 12px;
                font-weight: bold;
                display: inline-block;
            }
            .user-role-admin {
                background: #eaf3fa;
                color: #1976d2;
                border-radius: 5px;
                padding: 4px 12px;
                font-weight: bold;
                display: inline-block;
            }
            .user-role-user {
                background: #f0f0f0;
                color: #333;
                border-radius: 5px;
                padding: 4px 12px;
                font-weight: bold;
                display: inline-block;
            }
            .user-action-link {
                display: inline-block;
                margin: 2px 4px;
                padding: 6px 14px;
                border-radius: 4px;
                font-size: 15px;
                text-decoration: none;
                transition: background 0.2s, color 0.2s;
            }
            .user-edit-link {
                color: #5882eb;
                border: 1px solid #5882eb;
            }
            .user-edit-link:hover {
                background: #5882eb;
                color: #fff;
            }
            .user-delete-link {
                color: #fa4343;
                border: 1px solid #fa4343;
            }
            .user-delete-link:hover {
                background: #fa4343;
                color: #fff;
            }
        </style>
    </section>
<?php endif; ?>
