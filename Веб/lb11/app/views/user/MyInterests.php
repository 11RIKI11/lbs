<?php
$title = 'Мои интересы';
?>

<script src="public/js/MyInterests.js"></script>
<div id="top"></div>
<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<nav id="interests-contents">
    <?php foreach ($interestsCategories as $index => $category): ?>
        <a href="#my-interests-<?= $category['categoryid'] ?>"><?= $category['categoryname'] ?></a>
    <?php endforeach; ?>
</nav>
<?php foreach ($interestsCategories as $index => $category): ?>
    <section class="page-box" id="my-interests-<?= $category['categoryid'] ?>">
        <h2><?= $category['categoryname'] ?></h2>
        <p><?= $category['description'] ?></p>
        <?php 
        $interests = InterestsModel::findAllByFieldAssoc('categoryid', $category['categoryid']);
        if (isset($interests) && !empty($interests)): 
        ?>
            <div id="favorite-<?= $category['categoryid'] ?>"></div>
            <script>
                var interests = <?= json_encode($interests) ?>;
                createList("Любимое из этого", "favorite-<?= $category['categoryid'] ?>", interests);
            </script>
        <?php endif; ?>
        <div class="go-to-up-button">
            <a href="#top">↑</a>
        </div>
    </section>
<?php endforeach; ?>