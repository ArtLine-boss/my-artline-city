<?php
/*
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=updatePriceMat&scr=1
 */

$log = new core_log('cron/updatePriceMat/logs/' . API::CurrentDate(CONSTANTS::NAME_DATE_FORMAT) . '.txt');
$errs = [];
$delimiter = ';';
try {
    $manager = new cron_updatePriceMat_Manager();
    $manager->proccess();
    $list = $manager->getListMat();

    $index = 0;
    $result = 'Номер п/п' . $delimiter . 'ИД материала' . $delimiter
        . 'Наименование материала' . $delimiter . 'Количество на складе' . $delimiter
        . 'Старая цена' . $delimiter . 'Новая цена' . $delimiter . 'ТТН' . $delimiter . 'Курс' . PHP_EOL;
    foreach ($list as $dto) {
        /** @var cron_updatePriceMat_Dto $dto */
        $mat = classes_MaterialAttr::oid($dto->matId);
        if(!$mat->getInit()) {
            $errs[] = 'Не удалось найти материал с ИД `' . $dto->matId . '`';
            continue;
        }
        $mat->M_PRICE = $dto->newPrice;
        /*if(null !== ($msg = $mat->store())) {
            $errs[] = 'Не удалось сохранить материал с ИД `' . $dto->matId . '`. ' . $msg;
            continue;
        }*/
        $result .= ($index++) . $delimiter . $dto->matId . $delimiter
            . $dto->matName . $delimiter . $dto->currentQ . $delimiter . $dto->price . $delimiter . $dto->newPrice
            . $delimiter . $dto->ttnNum . ' от ' . API::FormatDate($dto->ttnDate, CONSTANTS::REPORT_DATE_FORMAT) . $delimiter . $dto->currency . PHP_EOL;
    }

    $log->store($result, false);
} catch (Exception $ex) {
    $log->store('ERROR! Код: ' . $ex->getCode() . '. Файл: ' . $ex->getFile() . '. Строка: ' . $ex->getLine() . '. Ошибка: ' . $ex->getMessage(), false);
}

if(count($errs) > 0) {
    $log->store('--------------------------------------', false);
    $log->store('Ошибки:', false);
    $log->store(implode(PHP_EOL, $errs), false);
}

if(!$manager->mail($log->getFilename())) {
    $log = new core_log('cron/updatePriceMat/mail.err');
    $log->store('Результат не был отправлен');
}

?>