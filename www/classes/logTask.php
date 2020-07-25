<?php
class classes_logTask extends core_DBObject {
    var $id_prod = null;
    var $user_log = null;
    var $datetime = null;
    var $comm = null;
    var $prob = null;
    var $status_old = null;
    var $status_new = null;

    public function __construct()
    {
        parent::__construct('log_task', 'id', 'classes_logTask');
    }
}
?>