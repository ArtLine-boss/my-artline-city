<?php
class classes_firmParent extends core_DBObject {
    var $name = null;

    public function __construct()
    {
        parent::__construct('firm_parent', 'ID', 'classes_firmParent');
    }
}
?>
