<!DOCTYPE html>
<html>
    <head>
        <title>Survey System</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= HOME_URL ?>/resources/css/style.css">
        <script src="<?= HOME_URL ?>/resources/js/jquery-2.1.1.min.js"></script>
        <script src="<?= HOME_URL ?>/resources/js/script.js"></script>
    </head>
    <body>
        <ul class="menu">
            <li><a href="<?= HOME_URL ?>">Главная</a></li>
            <li><a href="<?= HOME_URL ?>/admin">Список опросов</a></li>
        </ul>
        <?php include SITE_PATH . 'templates' . DIRSEP . $args['template'] . '.php'; ?>
    </body>
</html>
