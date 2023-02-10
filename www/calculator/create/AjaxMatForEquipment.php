<?php
do {
    $ajaxObject = new core_Dto();

    if (!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
        $msg = 'Отсутствуют права на запрос к данным';
        $ajaxObject->setMsg($msg);
        break;
    }

    $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : 0;
    if (empty($equipment))
        $ajaxObject->setMsg("Пустой входной объект");
    else {
        $equipmentObject = new classes_equipment();
        if (null !== ($msg = $equipmentObject->loadById($equipment))) {
            $ajaxObject->setMsg($msg);
        } else {
            if (empty($equipmentObject->mater)) {
                $ajaxObject->setMsg('Для данного оборудования не заданы материалы');
                break;
            }
            $sql = "SELECT kl_mat.*
                        FROM kl_mat
                        WHERE kl_mat.id IN (" . trim($equipmentObject->mater, ',') . ")
                        ORDER BY kl_mat.parent";
            $kl_mat = new classes_klMat();
            $list_mat = $kl_mat->selectByQuery($sql);

            $return = "";
            $parent = -1;
            foreach ($list_mat as $k => $v) {
                if ($parent != $v->parent) {
                    if (!empty($return)) {
                        $return .= "</optgroup>";
                    }
                    $return .= "<optgroup label='" . $v->title_parent . "' name = 'optgr'>";
                    $return .= "<option value='" . $v->getId() . "' >$v->title</option>";
                    $parent = $v->parent;
                } else {
                    $return .= "<option value='" . $v->getId() . "'>$v->title</option>";
                }
            }

            $ajaxObject->setData($return);
        }
    }
} while (false);
?>