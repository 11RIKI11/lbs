<?php
$title = 'Мой блог';
?>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box">
        <div class="blog-container">
                <h2>Блог</h2>

                <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
    <div class="blog-post" data-post-id="<?= (int)$post['id'] ?>">
        <div class="post-header">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <div class="post-meta">
                        <span class="post-date" style="margin-left:auto;display:block;text-align:right;"><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
                        <?php if (!empty($post['author'])): ?>
                                <span class="post-author">Автор: <?= htmlspecialchars($post['author']) ?></span>
                        <?php endif; ?>
                </div>
        </div>
        <?php if (!empty($post['img'])): ?>
                <img src="<?= htmlspecialchars($post['img']) ?>" class="post-image" alt="<?= htmlspecialchars($post['title']) ?>">
        <?php endif; ?>
        <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <!-- Кнопки комментариев -->
        <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
            <button type="button"
                class="toggle-comments-btn"
                data-post-id="<?= (int)$post['id'] ?>"
                style="margin-bottom:8px;<?= empty($post['comments']) ? 'visibility:hidden;' : '' ?>">
                Показать комментарии (<?= count($post['comments']) ?>)
            </button>
            <button type="button" class="add-comment-btn" data-post-id="<?= (int)$post['id'] ?>">Добавить комментарий</button>
        </div>
        <!-- Комментарии к посту -->
        <?php if (!empty($post['comments'])): ?>
            <div class="blog-comments" style="margin-top:10px;">
                <div class="comments-list" id="comments-list-<?= (int)$post['id'] ?>" style="display:none;">
                    <h4 style="margin:0 0 8px 0; font-size:17px; color:#444;">Комментарии:</h4>
                    <?php foreach ($post['comments'] as $comment): ?>
                        <div class="blog-comment" style="margin-bottom:10px; padding:10px 14px; background:#f8fafc; border-radius:6px; border:1px solid #e1e1e1;">
                            <div style="font-size:14px; color:#222; margin-bottom:2px;">
                                <b><?= htmlspecialchars($comment['author']) ?></b>
                                <span style="color:#888; font-size:12px; margin-left:8px;"><?= htmlspecialchars(date('d.m.Y H:i', strtotime($comment['created_at']))) ?></span>
                            </div>
                            <div style="font-size:15px; color:#333;"><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

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
                <?php else: ?>
                        <div class="no-posts">
                                Пока нет записей в блоге.
                        </div>
                <?php endif; ?>
        </div>
</section>

<!-- Модальное окно для комментария -->
<div id="comment-modal" style="display:none;">
    <div class="comment-modal-content">
        <button id="close-comment-modal" class="close-modal-btn" title="Закрыть" type="button">×</button>
        <h3 style="margin-top:0; font-weight:600; text-align:center;">Добавить комментарий</h3>
        <textarea id="comment-text" rows="4" class="inputUncheck comment-textarea" placeholder="Введите ваш комментарий"></textarea>
        <button id="send-comment-btn" class="modal-send-btn" type="button">Отправить</button>
    </div>
</div>

<script>
// Открытие модального окна
document.querySelectorAll('.add-comment-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('comment-modal').style.display = 'flex';
        document.getElementById('comment-text').value = '';
        document.getElementById('comment-text').focus();
        document.getElementById('send-comment-btn').setAttribute('data-post-id', btn.getAttribute('data-post-id'));
    });
});
// Закрытие модального окна
document.getElementById('close-comment-modal').onclick = function() {
    document.getElementById('comment-modal').style.display = 'none';
};
document.getElementById('comment-modal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

// Отправка комментария через тег <script> с XML-данными
document.getElementById('send-comment-btn').onclick = function() {
    var postId = this.getAttribute('data-post-id');
    var commentText = document.getElementById('comment-text').value;
    var xml = '<comment><postId>' + encodeURIComponent(postId) + '</postId><text>' + encodeURIComponent(commentText) + '</text></comment>';
    var script = document.createElement('script');
    script.src = '/blog/postComment?xml=' + encodeURIComponent(xml) + '&cb=makeCommentComplete';

    window.makeCommentComplete = function() {
        var dv_Result = document.getElementById('comment-modal');
        if (typeof js_ErrCode !== 'undefined' && js_ErrCode) {
            alert(js_ErrMsg || "Ошибка");
        } else {
            alert(js_ErrMsg || "Комментарий добавлен");
            document.getElementById('comment-modal').style.display = 'none';

            // Добавить комментарий в DOM только для текущего поста
            var postId = document.getElementById('send-comment-btn').getAttribute('data-post-id');
            var postDiv = document.querySelector('.blog-post[data-post-id="' + postId + '"]');
            if (postDiv) {
                var commentsBlock = postDiv.querySelector('.blog-comments');
                var commentsList;
                if (!commentsBlock) {
                    commentsBlock = document.createElement('div');
                    commentsBlock.className = 'blog-comments';
                    var toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.className = 'toggle-comments-btn';
                    toggleBtn.setAttribute('data-post-id', postId);
                    toggleBtn.style.marginBottom = '8px';
                    toggleBtn.textContent = 'Показать комментарии (1)';
                    commentsBlock.appendChild(toggleBtn);

                    commentsList = document.createElement('div');
                    commentsList.className = 'comments-list';
                    commentsList.id = 'comments-list-' + postId;
                    commentsList.style.display = 'block';
                    commentsBlock.appendChild(commentsList);

                    postDiv.appendChild(commentsBlock);

                    toggleBtn.addEventListener('click', function() {
                        if (commentsList.style.display === 'none' || commentsList.style.display === '') {
                            commentsList.style.display = 'block';
                            toggleBtn.textContent = 'Скрыть комментарии';
                        } else {
                            commentsList.style.display = 'none';
                            toggleBtn.textContent = 'Показать комментарии (' + commentsList.querySelectorAll('.blog-comment').length + ')';
                        }
                    });
                } else {
                    commentsList = commentsBlock.querySelector('.comments-list');
                    if (!commentsList) {
                        commentsList = document.createElement('div');
                        commentsList.className = 'comments-list';
                        commentsList.id = 'comments-list-' + postId;
                        commentsList.style.display = 'block';
                        commentsBlock.appendChild(commentsList);
                    }
                    commentsList.style.display = 'block';
                    var toggleBtn = commentsBlock.querySelector('.toggle-comments-btn');
                    if (toggleBtn) toggleBtn.textContent = 'Скрыть комментарии';
                }

                var commentDiv = document.createElement('div');
                commentDiv.className = 'blog-comment';
                commentDiv.style.marginBottom = '10px';
                commentDiv.style.padding = '10px 14px';
                commentDiv.style.background = '#f8fafc';
                commentDiv.style.borderRadius = '6px';
                commentDiv.style.border = '1px solid #e1e1e1';

                var author = <?= json_encode($_SESSION['user']['fio'] ?? 'Неизвестный автор') ?>;
                var now = new Date();
                var dateStr = now.toLocaleDateString('ru-RU') + ' ' + now.toLocaleTimeString('ru-RU', {hour: '2-digit', minute:'2-digit'});

                var metaDiv = document.createElement('div');
                metaDiv.style.fontSize = '14px';
                metaDiv.style.color = '#222';
                metaDiv.style.marginBottom = '2px';
                metaDiv.innerHTML = '<b>' + author + '</b> <span style="color:#888; font-size:12px; margin-left:8px;">' + dateStr + '</span>';

                var contentDiv = document.createElement('div');
                contentDiv.style.fontSize = '15px';
                contentDiv.style.color = '#333';
                contentDiv.innerHTML = document.getElementById('comment-text').value.replace(/\n/g, '<br>');

                commentDiv.appendChild(metaDiv);
                commentDiv.appendChild(contentDiv);

                commentsList.appendChild(commentDiv);

                // Обновить счетчик на кнопке сразу после добавления
                var toggleBtn = commentsBlock.querySelector('.toggle-comments-btn');
                if (toggleBtn) {
                    var count = commentsList.querySelectorAll('.blog-comment').length;
                    toggleBtn.textContent = (commentsList.style.display === 'block')
                        ? 'Скрыть комментарии'
                        : 'Показать комментарии (' + count + ')';
                    // Если кнопка была скрыта (visibility:hidden), показать её
                    toggleBtn.style.visibility = 'visible';
                }
            }
        }
        script.remove();
    };

    document.body.appendChild(script);
};

// Кнопка показать/скрыть комментарии
document.querySelectorAll('.toggle-comments-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var postId = btn.getAttribute('data-post-id');
        var commentsDiv = document.getElementById('comments-list-' + postId);
        if (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') {
            commentsDiv.style.display = 'block';
            btn.textContent = 'Скрыть комментарии';
        } else {
            commentsDiv.style.display = 'none';
            btn.textContent = 'Показать комментарии (' + commentsDiv.querySelectorAll('.blog-comment').length + ')';
        }
    });
});
</script>

<style>
.blog-post {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        background-color: #fff;
}

.post-header h3 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 24px;
}

.post-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        color: #666;
        font-size: 14px;
        flex-direction: column;
        justify-content: space-between;
}

.post-date {
        color: #888;
}

.post-image {
        max-width: 100%;
        height: auto;
        margin-bottom: 15px;
        border-radius: 4px;
}

.post-content {
        line-height: 1.6;
        color: #444;
}
.toggle-comments-btn,
.add-comment-btn {
    background: #5882eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 18px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    font-weight: 500;
    letter-spacing: 0.5px;
    box-shadow: none;
    outline: none;
    margin: 0 0 0 0;
    border: 1.5px solid #3a5fc7;
}
.toggle-comments-btn:hover,
.add-comment-btn:hover {
    background: #3a5fc7;
}
.toggle-comments-btn {
    margin-right: 12px;
}
#comment-modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.13);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
#comment-modal[style*="display: flex"] {
    display: flex !important;
}
.comment-modal-content {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.13);
    max-width: 420px;
    width: 94vw;
    margin: auto;
    padding: 28px 24px 22px 24px;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    border: 1px solid #e1e1e1;
    align-items: stretch;
}
.comment-modal-content h3 {
    margin: 0 0 8px 0;
    font-size: 22px;
    color: #222;
    text-align: center;
    font-weight: 600;
}
.comment-textarea {
    width: 100%;
    min-height: 80px;
    font-size: 16px;
    border-radius: 6px;
    border: 1.5px solid #bfc8d6;
    padding: 8px 10px;
    resize: vertical;
    background: #f8fafc;
    color: #222;
    transition: border 0.2s, background 0.2s;
    margin-bottom: 8px;
    box-sizing: border-box;
}
.comment-textarea:focus {
    border-color: #222;
    outline: none;
    background: #fff;
}
.inputUncheck, .comment-textarea {
    border: 1.5px solid #bfc8d6;
    border-radius: 6px;
    background: #f8fafc;
    color: #222;
    font-size: 16px;
    padding: 8px 10px;
    transition: border 0.2s, background 0.2s;
}
.inputUncheck:focus, .comment-textarea:focus {
    border-color: #222;
    background: #fff;
    outline: none;
}
.modal-send-btn {
    background: #5882eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 0;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
    width: 100%;
    margin-top: 4px;
    font-weight: 500;
    letter-spacing: 0.5px;
}
.modal-send-btn:hover {
    background: #3a5fc7;
}
.close-modal-btn {
    position: absolute;
    top: 10px;
    right: 14px;
    background: none;
    border: none;
    font-size: 26px;
    color: #888;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0;
    line-height: 1;
}
.close-modal-btn:hover {
    color: #fa4343;
}
</style>