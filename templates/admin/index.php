<h3>Список опросов</h3>

<p><a href="<?= HOME_URL ?>/admin/create">Создать новый опрос</a></p>

<?php foreach ($args['polls'] as $k => $group) : ?>
    <h4><?= $k == 'active' ? 'Активные' : ($k == 'draft' ? 'Черновики' : 'Закрытые опросы') ?></h4>
    <?php
    if ($k == 'active') {
        $has_active = true;
    }
    ?>
    <ul class="polls">
        <?php foreach ($group as $poll) : ?>
            <li>
                <b><?= $poll['title'] ?></b>
                <?php if ($k == 'active') : ?>
                    <a href="<?= HOME_URL ?>/admin/results/<?= $poll['id'] ?>">Просмотреть результаты</a>
                    <a href="<?= HOME_URL ?>/admin/close/<?= $poll['id'] ?>">Закрыть опрос</a>
                    <a href="<?= HOME_URL ?>/admin/delete/<?= $poll['id'] ?>">Удалить опрос</a>
                <?php elseif ($k == 'draft'): ?>
                    <a href="<?= HOME_URL ?>/admin/create/<?= $poll['id'] ?>">Редактировать опрос</a>
                    <?= !isset($has_active) ? '<a href="' . HOME_URL . '/admin/activate/' . $poll['id'] . '">Активировать опрос</a>' : '' ?>
                    <a href="<?= HOME_URL ?>/admin/delete/<?= $poll['id'] ?>">Удалить опрос</a>
                <?php else: ?>
                    <a href="<?= HOME_URL ?>/admin/results/<?= $poll['id'] ?>">Просмотреть результаты</a>
                    <?= !isset($has_active) ? '<a href="' . HOME_URL . '/admin/activate/' . $poll['id'] . '">Активировать опрос</a>' : '' ?>
                    <a href="<?= HOME_URL ?>/admin/delete/<?= $poll['id'] ?>">Удалить опрос</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>