<?php

/**
 * Глобальная переменная с инфой по сессии
 */
class core_sessionInfo extends core_Singleton
{
    private $user = null;

    protected function __construct()
    {
        $this->user = classes_users::newByLogin($_SESSION['login']);
    }

    /**
     * Получение параметра для пользователя
     * @param $field
     * @return mixed|null
     */
    public function getInfoByField($field)
    {
        return $this->user->getInfoByField($field);
    }

    /**
     * Установка параметра для пользователя
     * @param $field
     * @param $value
     * @return void
     */
    public function setInfoByField($field, $value)
    {
        $this->user->setInfoByField($field, $value);
        $this->user->store();
    }
}