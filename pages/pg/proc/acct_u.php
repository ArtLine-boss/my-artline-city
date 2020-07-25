<?php
	require_once '../../../PHPWord/PHPWord.php';

$word = new PHPWord();

$word->setDefaultFontName('Times New Roman');
$word->setDefaultFontSize(14);


$section = $word->createSection();

$fontStyle = array('color'=>'FFFF00', 'size'=>18, 'bold'=>true);
$section->addText('Привет!', $fontStyle);


$section->save('document.docx'); //Сохраняем результат в файл






?>