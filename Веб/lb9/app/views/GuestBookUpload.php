<?php
$title = 'Загрузка сообщений гостевой книги';
?>

<style>
.success-message {
    color: #28a745;
    font-size: 14px;
}
</style>

<h1 class="page-title"><?= $title ?></h1>

<section class="page-box form-box" id="guest-book-upload-box">
        <h2>Загрузка файла сообщений</h2>

        <form method="POST" action="/guestbook/upload" enctype="multipart/form-data" novalidate>
            <div class="file-upload">
                <label for="messages_file">
                    Выберите файл
                    <input type="file" id="messages_file" name="messages_file" accept=".inc" required class="inputUncheck">
                </label>
                <div class="file-name" id="file-name-display">Файл не выбран</div>
                <?php if (!empty($errors)): ?>
                    <p class="error-message"> <?= $errors[0] ?> </p>
                <?php elseif(isset($success) && $success): ?>
                    <p class="success-message"> <?= 'Файл успешно загружен' ?> </p>
                <?php else: ?> <br>
                <?php endif; ?>
            </div>
            
            <div class="note-box">
                <p>Каждая строка должна содержать запись в формате:</p>
                <code>дата;фио;email;сообщение</code>
                <p>Пример:</p>
                <code>2024-01-20 15:30:00;Иванов Иван;ivan@example.com;Текст сообщения</code>
            </div>

            <div class="button-box">
                <button type="submit">Загрузить</button>
            </div>
        </form>
</section>

<script>
document.getElementById('messages_file').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
    document.getElementById('file-name-display').textContent = fileName;
});
</script> 