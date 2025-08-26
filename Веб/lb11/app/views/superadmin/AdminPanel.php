<?php
     $title = 'Администрирование';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box">
    <h2>Панель администратора</h2>
    <ul style="list-style:none; padding:0;">
        <li>
            <a href="/admin/test/results" class="admin-panel-link">Результаты тестирования</a>
        </li>
        <li>
            <a href="/admin/users" class="admin-panel-link">Пользователи</a>
        </li>
        <li>
            <a href="/admin/visitors" class="admin-panel-link">Посетители</a>
        </li>
        <!-- Добавьте другие разделы по необходимости -->
    </ul>
</section>

<style>
.admin-panel-link {
    display: inline-block;
    margin: 10px 0;
    padding: 12px 24px;
    background: #f5f5f5;
    color: #5882eb;
    border-radius: 6px;
    text-decoration: none;
    font-size: 20px;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
    letter-spacing: 1px;
    box-shadow: 0 2px 8px rgba(88,130,235,0.07);
}
.admin-panel-link:hover {
    background: #5882eb;
    color: #fff;
    box-shadow: 0 4px 16px rgba(88,130,235,0.13);
    transform: translateY(-2px) scale(1.04);
}
</style>


