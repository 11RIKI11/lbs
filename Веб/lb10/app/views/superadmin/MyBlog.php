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

                <button class="add-post-button" type="button" onclick="window.location.href='/blog/add'">Добавить пост</button><br><br>

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
                                        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                                                <a href="/blog/delete?id=<?= (int)$post['id'] ?>" class="delete-post-link" style="color: #fa4343; text-decoration: none; font-size: 15px; border: 1px solid #fa4343; border-radius: 4px; padding: 6px 14px; background: #fff; transition: background 0.2s, color 0.2s;">Удалить</a>
                                        </div>
                                        <!-- id поста доступен через data-post-id, можно использовать для js -->
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

        .delete-post-link {
                color: #fa4343;
                text-decoration: none;
                font-size: 15px;
                border: 1px solid #fa4343;
                border-radius: 4px;
                padding: 6px 14px;
                background: #fff;
                transition: background 0.2s, color 0.2s;
        }
        .delete-post-link:hover,
        .delete-post-link:focus {
                background: #fa4343;
                color: #fff;
                border-color: #fa4343;
        }
</style>