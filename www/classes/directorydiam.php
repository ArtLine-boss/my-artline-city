<?php
class classes_directorydiam extends core_DBObject {
    var $name = null;
    var $value = null;

    public function __construct()
    {
        parent::__construct('directorydiam', 'ID', 'classes_directorydiam');
    }
}
?>