<?php 
        $title = 'Главная страница';
?>
<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
        <h1 class="page-title"><?=$title?></h1>
        <section class="page-box" id="profile-info">
            <div class="text">
                <h2>Боров Максим Геннадьевич</h2>
                <p><strong>Группа:</strong> ИС/б-22-1-о</p>
                <p><strong>Лабораторная работа №11:</strong> Исследование возможностей асинхронного взаимодействия с сервером. Технология AJAX</p>
            </div>
            <div class="photo">
                <img src="public/images/albom/profile.jpg"
                    alt="Фотография профиля" class="profile-photo">
            </div>
        </section>
