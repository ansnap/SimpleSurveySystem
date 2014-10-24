<?php if ($args['request']) : ?>
    <div class="strong">Выборка по пользователям:</div>
    <div><?= $args['request'] ?></div>
<?php endif; ?>

<?php if ($args['request'] && $args['no_users']) : ?>
    <h4>Нет результатов</h4>
<?php else: ?>
    <?php include '../results.php'; ?>
<?php endif; ?>

<h3>Выборка ответов</h3>

<form method="POST">
    <?php foreach ($args['poll']['questions'] as $q) : ?>
        <fieldset>
            <legend><?= $q['title'] ?></legend>
            <input type="hidden" name="questions[<?= $q['id'] ?>][title]" value="<?= $q['title'] ?>">
            <?php foreach ($q['answers'] as $a) : ?>
                <label>
                    <input type="checkbox" name="questions[<?= $q['id'] ?>][answers][<?= $a['id'] ?>]" value="<?= $a['title'] ?>">
                    <?= $a['title'] ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
    <?php endforeach; ?>
    <input type="submit" value="Посмотреть результаты">
</form>
