<?php
/** @var core_Dto $ajaxObject */

$invalidMaterial = classes_MaterialAttr::getInvalidTree();
$invalidTree = classes_klMat::getInvalidRow();

$ajaxObject->setData(['material' => $invalidMaterial, 'tree' => $invalidTree]);