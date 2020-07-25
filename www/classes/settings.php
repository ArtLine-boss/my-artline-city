<?php
    class classes_settings extends core_DBObject {
        var $NAME = null;
        var $VAL = null;

        public function __construct()
        {
            parent::__construct('settings', 'ID', 'classes_settings');
        }
    }
?>