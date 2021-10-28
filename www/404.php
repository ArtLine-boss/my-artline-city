<?php
include_once('core/core.php');

$webhook = entity_webhooks::loadWebhook($_SERVER['REDIRECT_URL'], false);

if(!$webhook->getInit() || !$webhook->includeWebhook()) {
    echo '
<!doctype html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
        <title>404 - Страница не найдена</title>
		<!-- ICON -->
		<link rel="icon" href="/favicon.png" type="image/png">
		<!-- Stylesheet -->
        <link rel="stylesheet" href="/bitrix24/css/main.css">
		<script src="/bitrix24/js/main.min.js"></script>
    </head>
    <body class="o-page o-page--center">

        <div class="o-page__card">

            <div class="c-card u-mb-small">
                <header class="c-card__header u-text-center u-pt-large" style="background-color:#000000">
                    <span class="c-card__icon" style="background:#fde118">
                        <img src="/dist/css/logo.png" alt="АртЛайнСити">
                    </span>
                    <h1 class="u-text-big u-mb-zero" style="color:#ffffff">
                        404 <em class="u-block u-text-large" style="color:#ffffff">Страница не найдена</em>
                    </h1>
                </header>

                <div class="c-card__body">
                    <h2 class="u-h5 u-text-center u-mb-medium">
                        Не удалось найти страницу. Убедитесь, что адрес введен верно. Возможно, страница не существует.
                    </h2>
					<img src="/dist/css/badsmile.png" alt="АртЛайнСити">
                </div>
            </div>
            
        </div>
    </body>
</html>';
}