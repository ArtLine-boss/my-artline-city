<?php
class classes_accordUsers extends core_DBObject
{
    public $user = null; // Пользователь, который добавил
    public $user1 = null; // Пользователь 1й компании
    public $user2 = null; // Пользователь 2й компании
    public $accord_type = null; // 0 - АртЛайн-Мечта, 1 - Мечта-АртЛайн
    public $status = null; // 1 - акутально
    public $date_start = null; // Дата старта соответствия
    public $date_cancel = null; // Дата аннулирования

    private $user_object = null;
    private $user1_object = null;
    private $user2_object = null;

    const ACCORD_TYPE_AM = 0; // АртЛайн-Мечта
    const ACCORD_TYPE_MA = 1; // Мечта-АртЛайн

    const ACCORD_STATUS_YES = 1; // актуальная запись
    const ACCORD_STATUS_NO = 2; // отмененная запись

    public function __construct()
    {
        parent::__construct('accord_users', 'ID', 'classes_accordUsers');
    }

    public function bind($value = null)
    {
        $msg = null;

        do {
            if(null !== ($msg = parent::bind($value))) break;
            $this->user_object = new classes_users();
            if(null !== ($msg = $this->user_object->LoadByLogin($this->user))) break;
            $this->user1_object = new classes_users();
            if(null !== ($msg = $this->user1_object->LoadByLogin($this->user1))) break;
            $this->user2_object = new classes_users();
            if(null !== ($msg = $this->user2_object->LoadByLogin($this->user2))) break;
        } while(false);

        return $msg;
    }

    /**
     * @return null|classes_users
     */
    public function getUserObject()
    {
        return $this->user_object;
    }

    /**
     * @return null|classes_users
     */
    public function getUser1Object()
    {
        return $this->user1_object;
    }

    /**
     * @return null|classes_users
     */
    public function getUser2Object()
    {
        return $this->user2_object;
    }

    /**
     * @param classes_users|null $_user1
     * @param int $accordType
     * @return array
     */
    public static function getUsers2ByUser1($_user1, $accordType = 0)
    {
        $result = [];

        do {
            if(empty($_user1) || get_class($_user1) != 'classes_users') break;
            $result[] = $_user1->getId();
            $class = new static();
            $list = $class->loadAll(['sql' => '`user1` = \'' . $_user1->USER_LOGIN . '\' AND `status` = ' . static::ACCORD_STATUS_YES . ' AND `accord_type` = ' . $accordType . ' AND `date_start` <= \'' . API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT) . '\'']);
            foreach ($list as $accord) {
                /** @var static $accord */
                $us = new classes_users();
                if(null !== ($msg = $us->LoadByLogin($accord->user2))) {
                    $result = [];
                    break(2);
                }
                $result[] = $us->getId();
            }
        } while(false);

        return $result;
    }

    /**
     * @return array
     */
    public static function loadAllByAM()
    {
        $class = new static();
        $result = $class->loadAll(['sql' => '`accord_type` = ' . static::ACCORD_TYPE_AM . ' AND `status` = ' . static::ACCORD_STATUS_YES . ' AND `date_start` <= \'' . API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT) . '\'']);
        unset($class);
        return $result;
    }

    /**
     * @return array
     */
    public static function loadAllByMA()
    {
        $class = new static();
        $result = $class->loadAll(['sql' => '`accord_type` = ' . static::ACCORD_TYPE_MA . ' AND `status` = ' . static::ACCORD_STATUS_YES . ' AND `date_start` <= \'' . API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT) . '\'']);
        unset($class);
        return $result;
    }
}
?>