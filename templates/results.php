<h3>Результаты опроса: "<?= $args['poll']['title'] ?>"</h3>

<ol class="poll-results">
    <?php foreach ($args['poll']['questions'] as $q) : ?>
        <li>
            <h4><?= $q['title'] ?></h4>
            <ul>
                <?php foreach ($q['answers'] as $a) : ?>
                    <li>
                        <div class="title"><?= $a['title'] ?></div>
                        <div class="bar"><div style="width:<?= $q['count'] != 0 ? $a['count'] / $q['count'] * 100 : 0 ?>%;">&nbsp;</div></div>
                        <div class="count"><?= $a['count'] ?> из <?= $q['count'] ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ol>