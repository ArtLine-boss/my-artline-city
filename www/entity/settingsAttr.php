<?php
class entity_settingsAttr extends core_DBObject
{
    public $SET_ID;
	public $DATE_VAL;
	public $VAL;

    public function __construct()
    {
        parent::__construct('settings_attr', 'ID', 'entity_settingsAttr');
    }

    /**
     * @param string|int $param
     * @param string $date
     * @return static
     */
    public static function getValByDate($param, $date) {
        $class = new static();
        do {
            if(empty($param) || empty($date)) {
                break;
            }
            $list = static::all(['sql' => '`SET_ID` = ' . intval($param) . ' AND `DATE_VAL` < \'' . API::FormatDate($date, CONSTANTS::DB_DATE_FORMAT) . '\''], null, 1, 1);
            if(isset($list[0])) {
                $class = $list[0];
            }
        } while(false);
        return $class;
    }
}
?>