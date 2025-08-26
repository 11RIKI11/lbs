<?php 
        $title = 'Редактор блога';
?>
<h1 class="page-title"><?=$title?></h1>
<section class="page-box form-box" id="blog-editor-add-box">
        <h2>Добавить пост</h2>
        <form method="POST" action="/blog/add" name="blog-editor" enctype="multipart/form-data" novalidate>
                <label for="title">Тема сообщения</label><br>
                <input type="text" id="title" name="title" class="inputUncheck" required
                        value="<?= htmlspecialchars($post['title'] ?? '') ?>">
                <?= isset($errorsTags['title']) ? ($errorsTags['title'][0] ?? '<br><br>') : '<br><br>'?>
                <div class="file-upload" id="img-file-upload">
                        <label for="imgField">Изображение
                        <input type="file" id="imgField" name="img" class="inputUncheck"?>
                        </label>
                        <div class="file-name" id="file-name-display">Файл не выбран</div>
                </div>
                <label for="messageInput">Текст сообщения</label><br>
                <textarea id="messageInput" name="inputMessage" rows="3" class="inputUncheck" required><?= htmlspecialchars($post['inputMessage'] ?? '') ?></textarea>
                <?= isset($errorsTags['content']) ? ($errorsTags['content'][0] ?? '<br><br>') : '<br><br>'?>

                <button type="submit">Отправить</button>
                <button type="reset">Очистить</button>
                <button type="button" onclick="window.location.href='/blog/add/upload'">Загрузить сообщения из файла</button>
        </form>
</section>

<script>
document.getElementById('img-file-upload').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
    document.getElementById('file-name-display').textContent = fileName;
});
</script> 