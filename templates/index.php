<h3>Главная</h3>

<?php if (isset($args['errors'])): ?>
    <ul class="errors">
        <?php
        foreach ($args['errors'] as $e) {
            echo '<li>' . $e . '</li>';
        }
        ?>
    </ul>
<?php endif; ?>

<?php if ($args['poll']): ?>
    <form method="POST">
        <h4>Опрос: "<?= $args['poll']['title'] ?>"</h4>
        <input type="hidden" name="id" value="<?= $args['poll']['id'] ?>">
        <?php foreach ($args['poll']['questions'] as $q) : ?>
            <input type="hidden" name="questions[<?= $q['id'] ?>][is_required]" value="<?= $q['is_required'] ?>">
            <fieldset>
                <legend><?= $q['title'] . ($q['is_required'] ? '*' : '') ?></legend>
                <?php foreach ($q['answers'] as $a) : ?>
                    <label>
                        <?php if ($q['is_multiple']): ?>
                            <input type="checkbox" name="questions[<?= $q['id'] ?>][answers][]" value="<?= $a['id'] ?>">
                        <?php else: ?>
                            <input type="radio" name="questions[<?= $q['id'] ?>][answers][]" value="<?= $a['id'] ?>">
                        <?php endif; ?>
                        <?= $a['title'] ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
        <input type="submit" value="Сохранить результаты">
    </form>
<?php else : ?>
    <p>Нет доступного опроса</p>
<?php endif; ?>
