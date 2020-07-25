<?php
    class classes_DizOper extends core_DBObject {
        var $NAME = null;
        var $TIME_ = null;
        var $DEFAULT_ = null;

        public function __construct()
        {
            parent::__construct('diz_oper', 'ID', 'classes_DizOper');
        }
    }
?>
