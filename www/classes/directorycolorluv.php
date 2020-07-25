<?php
class classes_directorycolorluv extends core_DBObject {
    var $name = null;
    var $value = null;

    public function __construct()
    {
        parent::__construct('directorycolorluv', 'ID', 'classes_directorycolorluv');
    }
}
?>
