<?php
$result = false;
$ajaxObject = new core_Dto();
do {
    if (!isset($_POST['idMat']) || !isset($_POST['idTree'])) {
        $ajaxObject->setMsg('Не передан обязательный параметр');
        break;
    }
    $material = classes_MaterialAttr::oid($_POST['idMat']);
    if (!$material->getInit()) {
        $ajaxObject->setMsg('Не найден материал');
        break;
    }
    $tree = classes_klMat::oid($_POST['idTree']);
    if (!$tree->getInit()) {
        $ajaxObject->setMsg('Не найден пункт в классификаторе');
        break;
    }
    $material->id_tree = $tree->getId();
    if (null !== ($msg = $material->store())) {
        $ajaxObject->setMsg('Ошибка сохранения. ' . $msg);
        break;
    }
    $result = true;
} while (false);

$ajaxObject->setData($result);