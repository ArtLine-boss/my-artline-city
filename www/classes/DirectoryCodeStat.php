<?php
class classes_DirectoryCodeStat extends core_DBObject {
    var $code_stat = null;
    var $name = null;
    var $deleteStatus = null;

    public function __construct()
    {
        parent::__construct('directorycodestat', 'ID', 'classes_DirectoryCodeStat');
    }

    public function loadAll() {
        $where = array('sql' => 'deleteStatus = 0', 'param' => null);
        return parent::loadAll($where, 'name');
    }
}
?>