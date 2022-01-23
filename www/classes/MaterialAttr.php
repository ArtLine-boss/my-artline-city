<?php

class classes_MaterialAttr extends core_DBObject
{
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

    /**
     * Проверка привязки к классификатору
     * @return array
     */
    public static function getInvalidTree()
    {
        $result = [];
        $list = static::all(['sql' => 'id_tree <> 0 AND id_tree IS NOT NULL']);
        foreach ($list as $mat) {
            /** @var static $mat */
            $tree = classes_klMat::oid($mat->id_tree);
            if (!$tree->getInit()) {
                $result[] = $mat;
            }
        }
        return $result;
    }
}

?>
