<?php
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>

<?php if (empty($results)): ?>
    <div class="page-box">
        <p>Нет попыток тестирования.</p>
    </div>
<?php else: ?>
    <section class="page-box">
        <table class="table-box">
            <thead>
                <tr>
                    <th>№</th>
                    <th>ФИО</th>
                    <th>Группа</th>
                    <th>Вопрос 1</th>
                    <th>Вопрос 2</th>
                    <th>Вопрос 3</th>
                    <th>Баллы</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Ошибки</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 + (($currentPage ?? 1) - 1) * ($perPage ?? 10) ?></td>
                        <td><?= htmlspecialchars($row['inputname']) ?></td>
                        <td><?= htmlspecialchars($row['studentgroup']) ?></td>
                        <td><?= htmlspecialchars($row['question1']) ?></td>
                        <td><?= htmlspecialchars($row['question2']) ?></td>
                        <td><?= htmlspecialchars($row['question3']) ?></td>
                        <td><?= (int)$row['score'] ?></td>
                        <td>
                            <?php if ($row['ispassed']): ?>
                                <span style="color:green;">Пройдено</span>
                            <?php else: ?>
                                <span style="color:red;">Не пройдено</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['submissiondate']) ?></td>
                        <td>
                            <?php
                            $errs = json_decode($row['errors'], true);
                            if (is_array($errs)) {
                                foreach ($errs as $field => $messages) {
                                    foreach ($messages as $msg) {
                                        echo htmlspecialchars($msg) . "<br>";
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (($totalPages ?? 1) > 1): ?>
            <div class="pagination">
                <?php
                $range = 1;
                $currentPage = $currentPage ?? 1;
                $totalPages = $totalPages ?? 1;

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