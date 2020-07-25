<?php
    class classes_SizePrint extends core_DBObject {
        var $SIZE = null;
        var $flags = null;

        public function __construct()
        {
            parent::__construct('size_print', 'ID', 'classes_SizePrint');
        }
    }
?>
