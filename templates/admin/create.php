<h3>Опрос</h3>

<form class="create-poll" method="POST">
    <?php if (isset($args['errors'])): ?>
        <ul class="errors">
            <?php
            foreach ($args['errors'] as $e) {
                echo '<li>' . $e . '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>

    <label>
        Название опроса
        <input type="text" name="title" value="<?= isset($args['poll']['title']) ? $args['poll']['title'] : '' ?>">
        <input type="hidden" name="id" value="<?= isset($args['poll']['id']) ? $args['poll']['id'] : '' ?>">
    </label>

    <p><a href="#add-question">Добавить вопрос</a></p>

    <?php

    function question($num = '{num}', $q = null)
    {
        ?>
        <fieldset class="question <?= $q ? '' : 'invisible' ?>">
            <label>
                Вопрос
                <input class="question-title" type="text" name="questions[<?= $num ?>][title]" value="<?= isset($q['title']) ? $q['title'] : '' ?>">
            </label>
            <label>
                <input class="question-required" type="checkbox" name="questions[<?= $num ?>][is_required]"
                       <?= !empty($q['is_required']) || !$q ? 'checked="checked"' : '' ?>>
                Обязательный
            </label>
            <div class="question-type">
                <label><input type="radio" name="questions[<?= $num ?>][is_multiple]" value="0"
                              <?= (isset($q['is_multiple']) && !$q['is_multiple']) || !isset($q['is_multiple']) ? 'checked="checked"' : '' ?>>
                    Единичный выбор
                </label>
                <label><input type="radio" name="questions[<?= $num ?>][is_multiple]" value="1"
                              <?= isset($q['is_multiple']) && $q['is_multiple'] ? 'checked="checked"' : '' ?>>
                    Множественный выбор
                </label>
            </div>
            <a href="#add-answer">Добавить ответ</a>
            <?php
            if ($q && isset($q['answers'])) {
                foreach ($q['answers'] as $a) {
                    answer($num, $a);
                }
            }
            ?>
        </fieldset>
    <?php } ?>

    <?php

    function answer($num = '{num}', $a = null)
    {
        ?>
        <label class="answer <?= isset($a) ? '' : 'invisible' ?>">
            <input type="text" name="questions[<?= $num ?>][answers][][title]" value="<?= isset($a['title']) ? $a['title'] : '' ?>">
        </label>
    <?php } ?>

    <?= question() ?>
    <?= answer() ?>

    <?php
    if (isset($args['poll'])) {
        foreach ($args['poll']['questions'] as $num => $q) {
            echo question($num, $q);
        }
    }
    ?>

    <input type="submit" value="Сохранить">
</form>