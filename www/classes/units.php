<?php
    class classes_units extends core_DBObject {
        var $Name = null;
        var $codeC = null;

        public function __construct()
        {
            parent::__construct('units', 'ID', 'classes_units');
        }
    }
?>
