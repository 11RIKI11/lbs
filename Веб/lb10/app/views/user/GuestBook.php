<?php
$title = 'Гостевая книга';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box" id="guest-book-box">
        <h2>Оставить отзыв</h2>
        <form method="POST" action="/guestbook" name="guest-book" novalidate>
                <label for="name">ФИО</label><br>
                <input type="text" 
                       id="name" 
                       name="inputName" 
                       class="inputUncheck" 
                       readonly
                       value="<?= htmlspecialchars($_SESSION['user']['fio']) ?>">
                <br><br>

                <label for="emailInput">E-mail</label><br>
                <input type="email" 
                       id="emailInput" 
                       name="email" 
                       class="inputUncheck" 
                       readonly
                       value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
                <br><br>

                <label for="messageInput">Текст отзыва</label><br>
                <textarea id="messageInput" name="messageInput" rows="3" class="inputUncheck" required><?= htmlspecialchars($formData['messageInput'] ?? '') ?></textarea>
                <?= isset($errorsTags['messageInput']) ? ($errorsTags['messageInput'][0] ?? '<br><br>') : '<br><br>'?>

                <button type="submit">Отправить</button>
                <button type="reset">Очистить</button>
        </form>
</section>

<section class="page-box">
        <h2>Сообщения</h2>
        <?php if (empty($messages)): ?>
                <p>Пока нет сообщений</p>
        <?php else: ?>
        <table class="table-box">
                <thead>
                        <tr>
                            <th>№</th>
                            <th>Дата</th>
                            <th>ФИО</th>
                            <th>E-mail</th>
                            <th style="width: 50%;">Сообщение</th>
                        </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($message['date']) ?></td>
                            <td><?= htmlspecialchars($message['fio']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td style="word-wrap: break-word; max-width: 300px;"><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
        </table>
        <?php endif; ?>
</section>