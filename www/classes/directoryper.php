<?php
class classes_directoryper extends core_DBObject {
    var $name = null;
    var $value = null;
    var $nadb_2 = null;
    var $nadb_3 = null;
    var $nadb_5 = null;
    var $nadb_default = null;

    public function __construct()
    {
        parent::__construct('directoryper', 'ID', 'classes_directoryper');
    }
}
?>