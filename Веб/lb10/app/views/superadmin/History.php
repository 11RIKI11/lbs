<?php
$title = 'История просмотра';
?>

<script src="public/js/history.js"></script>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box">
    <h2>История текущего сеанса</h2>
    <table id="session-history">
        <thead>
            <tr>
                <th>Страница</th>
                <th>Посещения</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <h2>История за все время</h2>
    <table id="all-time-history">
        <thead>
            <tr>
                <th>Страница</th>
                <th>Посещения</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</section>