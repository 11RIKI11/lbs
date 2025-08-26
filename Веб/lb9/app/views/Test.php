<?php
$title = 'Тест по дисциплине "Инженерная графика"';

function selected($value, $comboBox, $formData) {
    return isset($formData[$comboBox]) && $formData[$comboBox] === $value ? 'selected' : '';
}
?>


<script src="public/js/test.js"></script>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box" id="test-form-box">
    <form method="post" action="/test" name="test">
        <label for="name-select">Фамилия Имя Отчество:</label><br>
        <input type="text" id="name-select" name="inputName" class="inputUncheck" value="<?= isset($formData) ? ($formData['inputName'] ?? '') : '' ?>">
        <?= isset($errorsTags['inputName']) ? ($errorsTags['inputName'][0] ?? '<br><br>') : '<br><br>'?>

        <label for="group-select">Группа:</label><br>
        <select id="group-select" name="studentGroup" class="inputUncheck" value="<?= isset($formData) ? ($formData['group'] ?? '<br><br>') : '<br><br>' ?>">
            <option value="">Выберите группу</option>
            <optgroup label="3 курс">
                <option value="ИС/б-22-1-о" <?= isset($formData) ? selected('ИС/б-22-1-о', 'studentGroup', $formData) : '' ?>>ИС/б-22-1-о</option>
                <option value="ИС/б-22-2-о" <?= isset($formData) ? selected('ИС/б-22-2-о', 'studentGroup', $formData) : '' ?>>ИС/б-22-2-о</option>
            </optgroup>
            <optgroup label="4 курс">
                <option value="ИС/б-21-1-о" <?= isset($formData) ? selected('ИС/б-21-1-о', 'studentGroup', $formData) : '' ?>>ИС/б-21-1-о</option>
                <option value="ИС/б-21-2-о" <?= isset($formData) ? selected('ИС/б-21-2-о', 'studentGroup', $formData) : '' ?>>ИС/б-21-2-о</option>
            </optgroup>
        </select>

        <?= isset($errorsTags['studentGroup']) ? ($errorsTags['studentGroup'][0] ?? '<br><br>') : '<br><br>' ?>

        <h2>Вопросы по дисциплине</h2>
        <div id="question1" class="radio-group">
            <label>Вопрос 1: Какое из приведённых ниже утверждений является верным?</label><br>
            <input type="radio" id="answer1" name="question1" value="answer1" class="radio-uncheck" <?= (isset($formData['question1']) && $formData['question1'] === 'answer1') ? 'checked' : '' ?>>
            <label for="answer1" class="answer-label">Карандаш является основным инструментом для создания чертежей вручную</label><br>
            <input type="radio" id="answer2" name="question1" value="answer2" class="radio-uncheck" <?= (isset($formData['question1']) && $formData['question1'] === 'answer2') ? 'checked' : '' ?>>
            <label for="answer2" class="answer-label">Невозможно создать чертёж без использования компьютера</label>

            <?= isset($errorsTags['question1']) ? ($errorsTags['question1'][0] ?? '<br><br>') : '<br><br>' ?>

        </div>
        <label for="question2">Вопрос 2: Как называется процесс переноса размеров из реального объекта на
            чертёж?</label><br>
        <select id="question2" name="question2" class="inputUncheck">
            <option value="">Выберите ответ</option>
            <option value="answer1" <?= isset($formData) ? selected('answer1', 'question2', $formData) : '' ?>>Проецирование</option>
            <option value="answer2" <?= isset($formData) ? selected('answer2', 'question2', $formData) : '' ?>>Измерение</option>
            <option value="answer3" <?= isset($formData) ? selected('answer3', 'question2', $formData) : '' ?>>Моделирование</option>
            <option value="answer4" <?= isset($formData) ? selected('answer4', 'question2', $formData) : '' ?>>Масштабирование</option>
        </select>

        <?= isset($errorsTags['question2']) ? ($errorsTags['question2'][0] ?? '<br><br>') : '<br><br>' ?>

        <label for="question3">Вопрос 3: Опишите основные этапы создания технического чертежа (Перечислите через запятую):</label><br>
        <textarea id="question3" name="question3" rows="5" class="inputUncheck"><?= isset($formData) ? ($formData['question3'] ?? '') : '' ?></textarea>

        <?= isset($errorsTags['question3']) ? ($errorsTags['question3'][0] ?? '<br><br>') : '<br><br>' ?>

        <button type="submit" name="submitButton">Отправить</button>
        <button type="reset" name="resetButton">Очистить форму</button>
    </form>
</section>