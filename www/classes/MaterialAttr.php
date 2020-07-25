<?php
    class classes_MaterialAttr extends core_DBObject {
        var $ID_M = null;
        var $M_NAME = null;
        var $M_PRICE = null;
        var $M_SIZE = null;
        var $M_UNIT = null;
        var $M_TOL = null;
        var $M_AVA = null;
        var $M_PR = null;
        var $M_KOL_ALL = null;
        var $M_KOL_JOB = null;
        var $M_NAME_ = null;
        var $M_PAR = null;
        var $id_tree = null;
        var $arh = null;
        var $size_rez = null;

        public function __construct()
        {
            parent::__construct('material_attr', 'ID', 'classes_MaterialAttr');
        }
    }
?>
