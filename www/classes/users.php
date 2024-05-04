<?php

class classes_users extends core_DBObject
{
    var $USER_LOGIN = null;
    var $USER_PASSWORD = null;
    var $USER_HASH = null;
    var $USER_IP = null;
    var $USER_FIO = null;
    var $USER_POST = null;
    var $USER_PER = null;
    var $USER_OP = null;
    var $USER_MAIL = null;
    var $DT1 = null;
    var $DT2 = null;
    var $cl_id = null;
    var $id_bitrix24 = null;
    var $reset_password = null;
    var $color_default = null;
    var $USER_STATUS = null;

    public function __construct()
    {
        parent::__construct('users', 'ID');
    }

    public function LoadByLogin($login)
    {
        $msg = null;

        do {
            $where = array(
                'sql' => 'user_login=:user_login AND USER_STATUS=:USER_STATUS',
                'param' => array(
                    'user_login' => $login,
                    'USER_STATUS' => 1
                )
            );
            $currentUser = $this->loadAll($where, null, null, 1);
            if (count($currentUser) !== 1) {
                $msg = 'Пользователь не найден';
                break;
            }
            if (null !== ($msg = ($this->bind($currentUser[0]))))
                break;
        } while (false);

        return $msg;
    }

    /**
     * Получение списка дизайнеров
     * @return static[]
     */
    public static function getListDesign()
    {
        $class = new static();
        return $class->loadAll(
            [
                'sql' => 'USER_PER = 5 AND USER_STATUS = 1'
            ],
            'USER_FIO'
        );
    }
}
