<?php
/** @var core_ui $AppUI */
/** @var core_Dto $ajaxObject */

if ($AppUI->isAdmin()) {
    $invalidMaterial = classes_MaterialAttr::getInvalidTree();
    $invalidTree = classes_klMat::getInvalidRow();

    $ajaxObject->setData(['material' => $invalidMaterial, 'tree' => $invalidTree]);
} else {
    $ajaxObject->setMsg('Отсутствует доступ к функционалу. Обратитесь к администратору системы.');
}