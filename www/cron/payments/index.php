<?php
/*
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=payments&scr=1
 */
    //логирование
    $log = new core_log('cron/payments/payment.log');
    //папка исходных файлов
    $source = 'cron/payments/source/';
    //папка архивных файлов
    $archive = 'cron/payments/archive/';
    //массив файлов txt
    $files = glob($source . "*.txt");

    if(count($files) == 0) {
        $log->store('Не найдены файлы для загрузки');
    } else {
        foreach ($files as $filesource) {
            //извлекаем имя файла
            $filename = basename($filesource);
            //копируем в архив
            $path_archive = $archive . API::CurrentDate(CONSTANTS::DB_DATE_FORMAT) . '/';
            if (!file_exists($path_archive)) {
                if (!mkdir($path_archive)) {
                    $log->store('Файл ' . $filesource . ': не удалось создать каталог для архива');
                    continue;
                }
            }
            copy($filesource, $path_archive . $filename);

            //открываем файл
            $handle = @fopen($filesource, "r");
            $count_pay = 0;
            if ($handle) {
                while (($buffer = fgets($handle, 4096)) !== false) {
                    API::toUTF8($buffer);
                    $explode = explode('~', $buffer);
                    $payment = new classes_oplati();
                    if ((null !== ($msg = $payment->bindByScript($explode, $filename, $log))) || (null !== ($msg = $payment->store()))) {
                        $log->store($msg);
                        continue;
                    }
                    $count_pay++;
                }
                if (!feof($handle)) {
                    $log->store('Файл ' . $filesource . ': не удалось считать данные в файле');
                }
                fclose($handle);
            }

            //удаляем файл
            $msg = unlink($filesource) ? 'Файл ' . $filesource . ': успешно обработан! Всего записей - ' . $count_pay : 'Файл ' . $filesource . ': не удалось удалить файл';
            //логирование успешной работы
            $log->store($msg);
        }
    }
?>
