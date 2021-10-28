<?php
class entity_webhooks extends core_DBObject
{
    /**
     * Наименование вебхука
     * @var string|null
     */
    public $name = null;
    /**
     * URL-адрес вебхука
     * @var string|null
     */
    public $url = null;

    public function __construct()
    {
        parent::__construct('webhooks', 'ID', 'entity_webhooks');
    }

    /**
     * Загружает вебхук по адресу
     * @param string $url Адрес
     * @param bool $exc Выбрасывать исключение или нет
     * @return static
     * @throws Exception
     */
    public static function loadWebhook($url = '', $exc = true) {
        $class = static::unique('url', $url);
        if(!$class->getInit() && $exc) {
            throw new Exception('Не удалось инициализировать вебхук');
        }
        return $class;
    }

    /**
     * Доступ
     * @return bool
     */
    private function access() {
        return true;
    }

    /**
     * Запуск вебхука
     * @return bool
     */
    public function includeWebhook() {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/www/webhooks/' . trim($this->url, "/") . '/index.php';
        if(file_exists($filename) && $this->access()) {
            include_once($filename);
            return true;
        } else {
            return false;
        }
    }
}