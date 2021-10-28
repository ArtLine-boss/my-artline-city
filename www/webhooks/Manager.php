<?php
abstract class webhooks_Manager extends core_Singleton
{
    /**
     * @var entity_webhooks|null
     */
    protected $webhook = null;

    /**
     * @param entity_webhooks|null $webhook
     */
    public function setWebhook($webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * @return entity_webhooks|null
     */
    public function getWebhook()
    {
        return $this->webhook;
    }

    /**
     * Отображение вебхука
     */
    public function view() {
        echo $this->getHeader();
        echo $this->getBody();
        echo $this->getFooter();
    }

    /**
     * Возврат заголовка
     * @return string
     */
    protected function getHeader() {
        $urlWh = trim($this->webhook->url, "/");
        $cssFiles = API::search_file_extension($_SERVER['DOCUMENT_ROOT'] . '/www/webhooks/' . $urlWh, 'css');
        $cssLinks = '';
        foreach ($cssFiles as $css) {
            $css = '/www/webhooks/' . $urlWh . '/' . substr($css, strpos($css, $urlWh) + strlen($urlWh) + 1);
            $cssLinks .= (!empty($cssLinks) ? PHP_EOL : '') . '<link rel="stylesheet" href="' . $css . '">';
        }

        return '
            <!doctype html>
            <html lang="ru">
            <head>
                <meta charset="utf-8">
                <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,600" rel="stylesheet">
                <link rel="stylesheet" href="/www/css/main.min.css">
                ' . $cssLinks . '
            </head>
            <body style="font-family: \'Roboto\'">
        ';
    }

    /**
     * @return string
     */
    abstract protected function getBody();

    /**
     * Возврат окончания страницы
     * @return string
     */
    protected function getFooter() {
        $urlWh = trim($this->webhook->url, "/");
        $jsFiles = API::search_file_extension($_SERVER['DOCUMENT_ROOT'] . '/www/webhooks/' . $urlWh, 'js');
        $jsScripts = '';
        foreach ($jsFiles as $js) {
            $js = '/www/webhooks/' . $urlWh . '/' . substr($js, strpos($js, $urlWh) + strlen($urlWh) + 1);
            $jsScripts .= (!empty($jsScripts) ? PHP_EOL : '') . '<script src="' . $js . '"></script>';
        }

        return '
            <script src="/www/js/main.min.js"></script>
            ' . $jsScripts . '
            ' . $this->getFooterScript() . '
            </body>
            </html>
        ';
    }

    /**
     * Функция для скриптов из кода PHP
     * @return string
     */
    protected function getFooterScript() {
        return '';
    }
}