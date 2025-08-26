<?php
$title = 'Редактор блога';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box form-box" id="blog-editor-add-box">
    <h2>Загрузить сообщения блога</h2>

    <form method="POST" action="/blog/add/upload" enctype="multipart/form-data" novalidate>
        <div class="file-upload">
            <label for="blog_file">
                Выберите файл
                <input type="file" id="blog_file" name="blog_file" accept=".csv" required class="inputUncheck">
            </label>
            <div class="file-name" id="file-name-display">Файл не выбран</div>
            <div class="note-box">
                <p>Каждая строка должна содержать запись, соответствующую одной записи блога. Поля должны разделяться запятыми.</p>
                <p>Формат:</p>
                <code>title,message,author,created_at</code>
                <p>Пример:</p>
                <code>Заголовок поста,Текст поста,Автор поста,2024-01-20 15:30:00</code>
            </div>
            <?php if (!empty($errors)): ?>
                <p class="error-message"> <?= $errors[0] ?> </p>
            <?php elseif (isset($success) && $success): ?>
                <p class="success-message"> <?= 'Файл успешно загружен' ?> </p>
            <?php else: ?> <br>
            <?php endif; ?>
        </div>

        <div class="button-box">
            <button type="submit">Загрузить</button>
        </div>
    </form>
</section>

<script>
document.getElementById('blog_file').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
    document.getElementById('file-name-display').textContent = fileName;
});
</script> 