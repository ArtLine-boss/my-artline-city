<?php
    class classes_prOper extends core_DBObject {
        var $NAME = null;
        var $TIME_ = null;
        var $DEFAULT_ = null;

        public function __construct()
        {
            parent::__construct('pr_oper', 'ID', 'classes_prOper');
        }
    }
?>