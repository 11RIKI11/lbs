<?php
$title = 'Посетители сайта';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?? 'Посетители сайта' ?></h1>

<?php
$perPage = 10;
$currentPage = isset($currentPage) ? (int)$currentPage : (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1);
$totalRows = isset($visitors) ? count($visitors) : 0;
$totalPages = max(1, ceil($totalRows / $perPage));
$offset = ($currentPage - 1) * $perPage;
$visitorsPage = array_slice($visitors ?? [], $offset, $perPage);
?>

<?php if (empty($visitorsPage)): ?>
    <div class="page-box">
        <p>Нет данных о посетителях.</p>
    </div>
<?php else: ?>
    <section class="page-box">
        <table class="table-box">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Дата и время</th>
                    <th>Страница</th>
                    <th>IP-адрес</th>
                    <th>Имя хоста</th>
                    <th>Браузер</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitorsPage as $i => $v): ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td><?= htmlspecialchars($v['visit_datetime']) ?></td>
                        <td><?= htmlspecialchars($v['page']) ?></td>
                        <td><?= htmlspecialchars($v['ip_address']) ?></td>
                        <td><?= htmlspecialchars($v['host']) ?></td>
                        <td style="max-width:300px;overflow-x:auto;"><?= htmlspecialchars($v['browser']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php
                $range = 1;
                if ($currentPage == 1) {
                    echo '<span class="page-link active">1</span>';
                } else {
                    echo '<a href="?page=1" class="page-link">1</a>';
                }
                if ($currentPage > $range + 2) {
                    echo '<span class="page-link">...</span>';
                }
                for ($i = max(2, $currentPage - $range); $i <= min($totalPages - 1, $currentPage + $range); $i++) {
                    if ($i == $currentPage) {
                        echo '<span class="page-link active">' . $i . '</span>';
                    } else {
                        echo '<a href="?page=' . $i . '" class="page-link">' . $i . '</a>';
                    }
                }
                if ($currentPage < $totalPages - $range - 1) {
                    echo '<span class="page-link">...</span>';
                }
                if ($currentPage == $totalPages) {
                    echo '<span class="page-link active">' . $totalPages . '</span>';
                } else {
                    echo '<a href="?page=' . $totalPages . '" class="page-link">' . $totalPages . '</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>