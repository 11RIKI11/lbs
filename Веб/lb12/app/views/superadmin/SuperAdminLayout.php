<?php
function isCurrentPage($uri) {
    if ($uri === '/') {
        return $_SERVER['REQUEST_URI'] === '/' ? 'current' : '';
    }
    return strpos($_SERVER['REQUEST_URI'], $uri) === 0 ? 'current' : '';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Персональный сайт Борова Максима. <?=$title?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="/public/js/general.js"></script>
</head>

<body>
    <header>
        <nav class="menu-box">
            <ul class="main-menu">
                <li>
                    <a href="/" class="<?=isCurrentPage('/')?>">Главная</a>
                </li>
                <li>
                    <a href="/aboutme" class="<?=isCurrentPage('/aboutme')?>">Обо мне</a>
                </li>
                <li id="my-interests" class="main-dropable">
                    <a class="drop-down-menu-title <?=isCurrentPage('/myinterests')?>" href="">Мои интересы</a>
					<ul class="dropdown-hidden">
                        <li>
                            <a href="/myinterests#top">Общее</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-music">Музыка</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-videogames">Видеоигры</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-films">Фильмы</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-series">Сериалы</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-books">Книги</a>
                        </li>
                        <li>
                            <a href="/myinterests#my-interests-boardgames">Настольные игры</a>
                        </li>
					</ul>
                </li>
                <li>
                    <a href="/photo" class="<?=isCurrentPage('/photo')?>">Фотоальбом</a>
                </li>
                <li>
                    <a href="/contact" class="<?=isCurrentPage('/contact')?>">Контакт</a>
                </li>
                <li>
                    <a href="/test" class="<?=isCurrentPage('/test')?>">Тест по дисциплине</a>
                </li>
                <li>
                    <a href="/guestbook" class="<?=isCurrentPage('/guestbook')?>">Гостевая книга</a>
                </li>
                <li>
                    <a href="/blog" class="<?=isCurrentPage('/blog')?>">Мой блог</a>
                </li>
                <li>
                    <a href="/admin" class="<?=isCurrentPage('/admin')?>">Администрирование</a>
                </li>
                <li class="current-date">
                    
                </li>
            </ul>
            <ul class="auth-menu">
                <li>
                    <a href="/logout" class="auth-button login">Выход</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <?php 
        include $content;
        ?>
    </main>
    <footer>
        <p>&copy; 2025 Боров Максим Геннадьевич</p>
    </footer>
</body>

</html>