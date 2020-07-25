<?php
/** Объект геттеров по таблицам */

class core_GetFilter
{
    public $_id = null; // ид таблицы
    public $sort = null; // столбец сортировки
    public $desc = false; // тип сортировки
    public $limit = null; // лимит записей
    public $page = null; // текущая страница

    public function __construct($tableId)
    {
        $this->_id = $tableId;
        $this->bindByGET();
    }

    protected function bindByGET() {
        $msg = null;

        do {
            if(!isset($_GET[$this->_id]) || empty($_GET[$this->_id])) {
                break;
            }
            if(!API::isJSON($_GET[$this->_id])) {
                break;
            }
            $_arr = json_decode($_GET[$this->_id]);
            foreach ($_arr as $k => $v) {
                if(property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        } while(false);

        $this->page = empty($this->page) ? 1 : $this->page;
        $this->limit = empty($this->limit) ? 25 : $this->limit;

        return $msg;
    }

    public function getJS() {
        $result = '<script> var $_TABLES_' . $this->_id . ' = {';
        foreach ($this as $k => $v) {
            $result .= '\'' . $k . '\': \'' . $v . '\',';
        }
        $result .= '}; </script>';
        return $result;
    }
}
?>