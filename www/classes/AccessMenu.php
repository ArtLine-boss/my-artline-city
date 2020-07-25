<?php
    class classes_AccessMenu extends core_DBObject {
        var $name = null;
        var $accessValue = null;
        var $url = null;
        var $parent_id = null;
        var $icon = null;

        public function __construct()
        {
            parent::__construct('accessmenu', 'ID', 'classes_AccessMenu');
        }
    }
?>
