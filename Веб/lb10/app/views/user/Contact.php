<?php
$title = 'Контакт';
?>

<script src="public/js/contact.js"></script>
<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box" id="contact-form-box">
    <form method="post" action="/contact" name="contact" novalidate>
        <div id="name-input-box">
            <label for="inputName">Фамилия Имя Отчество:</label><br>
            <input type="text" id="name" name="inputName" class="inputUncheck" value="<?= isset($formData) ? ($formData['inputName'] ?? '') : '' ?>">
            <?= isset($errorsTags['inputName']) ? ($errorsTags['inputName'][0] ?? '<br><br>') : '<br><br>'?>
        </div>
        <div id="gender" class="radio-group">
            <label>Пол:</label><br>
            <input type="radio" id="male" name="gender" value="male" <?= (isset($formData['gender']) && $formData['gender'] === 'male') ? 'checked' : '' ?>>
            <label for="male">Мужской</label><br>
            <input type="radio" id="female" name="gender" value="female" <?= (isset($formData['gender']) && $formData['gender'] === 'female') ? 'checked' : '' ?>>
            <label for="female">Женский</label><br>
            <?= isset($errorsTags['gender']) ? ($errorsTags['gender'][0] ?? '<br><br>') : '<br><br>'?>
        </div>
        <div id="birthday-input-box">
            <label for="birthdate">Дата рождения:</label>
            <input type="text" id="birthdate" name="birthdate" class="inputUncheck" readonly value="<?= isset($formData) ? ($formData['birthdate'] ?? '') : '' ?>">
            <div id="calendar" class="calendar-hidden">
                <div id="birth-month-year-container">
                    <select id="birth-month">

                    </select>
                    <select id="birth-year">

                    </select>
                </div>
                <div id="weekdays-container">

                </div>
                <div id="days-container">

                </div>
            </div>
            <?= isset($errorsTags['birthdate']) ? ($errorsTags['birthdate'][0] ?? '<br><br>') : '<br><br>'?>
        </div>

        <label for="phone-number">Номер телефона:</label>
        <input type="text" id="phoneNumber" name="phoneNumber" class="inputUncheck" value="<?= isset($formData) ? ($formData['phoneNumber'] ?? '') : '' ?>">
        <?= isset($errorsTags['phoneNumber']) ? ($errorsTags['phoneNumber'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" class="inputUncheck" value="<?= isset($formData) ? ($formData['email'] ?? '') : ''?>">
        <?= isset($errorsTags['email']) ? ($errorsTags['email'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="message">Сообщение:</label><br>
        <textarea id="message" rows="5" name="message" class="inputUncheck"><?= isset($formData) ? ($formData['message'] ?? '') : '' ?></textarea>
        <?= isset($errorsTags['message']) ? ($errorsTags['message'][0] ?? '<br><br>') : '<br><br>'?>

        <button type="submit" name="submitButton" class="modalOnButton">Отправить</button>
        <button type="reset" name="resetButton">Очистить форму</button>
        <!-- <div class="modal-overlay">
            <div class="modal">
                <p>Подтвердить отправку формы?</p>
                <button class="confirmModalButton">Подтвердить</button>
                <button class="closeModalButton">Отмена</button>
            </div>
        </div> -->
    </form>
</section>