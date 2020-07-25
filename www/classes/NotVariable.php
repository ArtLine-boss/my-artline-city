<?php
    class classes_NotVariable {
        var $List = array();

        public function __construct($query = null, $placeholders = null)
        {
            if(empty($query))
                return true;
            $obj = new core_DBObject(null, null, null);
            $list = $obj->select($query, $placeholders, true);
            foreach ($list as $k => $v) {
                $var = new classes_NotVariable();
                foreach ($v as $_k => $_v) {
                    $var->$_k = $_v;
                }
                unset($var->List);
                $this->List[] = $var;
            }
        }
    }
?>
