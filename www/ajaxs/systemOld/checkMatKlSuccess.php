<?php
/** @var core_ui $AppUI */
/** @var core_Dto $ajaxObject */

if ($AppUI->isAdmin()) {
    $errors = [];
    if ($_POST['material'] && is_array($_POST['material'])) {
        foreach ($_POST['material'] as $mat) {
            $material = classes_MaterialAttr::oid($mat['ID']);
            if ($material->getInit()) {
                $material->id_tree = 0;
                if (null !== ($msg = $material->store())) {
                    $errors[] = 'Материал с ИД ' . $mat['ID'] . ': ' . $msg;
                }
            } else {
                $errors[] = 'Материал с ИД ' . $mat['ID'] . ': не удалось найти объект';
            }
        }
    }
    if ($_POST['tree'] && is_array($_POST['tree'])) {
        foreach ($_POST['tree'] as $tree) {
            $treeClass = classes_klMat::oid($tree['id']);
            if ($treeClass->getInit()) {
                if (null !== ($msg = $treeClass->delete())) {
                    $errors[] = 'Пункт классификатора с ИД ' . $tree['ID'] . ': ' . $msg;
                }
            } else {
                $errors[] = 'Пункт классификатора с ИД ' . $tree['ID'] . ': не удалось найти объект';
            }
        }
    }

    if (count($errors) > 0) {
        $ajaxObject->setMsg(implode('. ', $errors));
    } else {
        $ajaxObject->setData(true);
    }
} else {
    $ajaxObject->setMsg('Отсутствует доступ к функционалу. Обратитесь к администратору системы.');
}