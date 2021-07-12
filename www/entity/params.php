<?php
class entity_params extends core_DBObject
{
    public $key_param = null;
    public $val = null;

    public function __construct()
    {
        parent::__construct('params', 'ID', 'entity_params');
    }

    /**
     * Запись параметра
     * @param string $_key
     * @param string $_val
     * @return string|null
     */
    public static function set($_key, $_val) {
        $class = static::get($_key);
        $class->key_param = $_key;
        $class->val = $_val;

        if(null !== ($msg = $class->store())) {
            return $msg;
        }

        return null;
    }

    /**
     * Возвращает параметр
     * @param string $_key
     * @return static
     */
    public static function get($_key) {
        $class = new static();
        $class->loadByUnique('key_param', $_key, true);
        return $class;
    }

    /**
     * @return string|null
     */
    public function store()
    {
        if(!$this->getInit()) {
            $class = static::get($this->key);
            if($class->getInit()) {
                $this->setId($class->getId());
            }
        }
        return parent::store();
    }
}
?>