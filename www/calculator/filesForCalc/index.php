<?php
/* 
 * Страница файлов для расчета
 */
//проверка доступа
$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC);
do {
    $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
    $bitrix = new classes_Bitrix24TemplateCalculation();
    if (null !== ($msg = $bitrix->loadById($id))) {
        echo $msg;
        break;
    }
    $current_files = "";
    $filepath = $bitrix->file_path;
    if(!empty($filepath)) {
        $files = glob($filepath . "/*");
        foreach ($files as $file) {
            $current_files .= "<tr><td>" . basename($file) . "</td>"
                    . "<td>" . ceil(filesize($file) / 1024) . "КБ</td>"
                    . "<td>" . date (CONSTANTS::REPORT_DATETIME_FORMAT, filemtime($file)) . "</td>"
                    . "<td><i class='fa fa-download' onclick='downloadFileInDir(this, \"" . $file . "\")'></i></td>"
                    . "<td><i class='fa fa-trash' onclick='deleteFileInDir(this, \"" . $file . "\")'></i></td></tr>";
        }
    } else {
        //создаем путь к файлам
        $filepath = 'calculator/orderToWork/files/calc_' . $bitrix->getId();
        API::createPath($filepath, $_SERVER['DOCUMENT_ROOT'] . "/www");
        $bitrix->file_path = $filepath;
        if(null !== ($msg = $bitrix->store())) {
            die($msg);
        }
    }
?>

<form class='form-signin' method='post' action='' enctype='multipart/form-data' id='forms'>
    <h3 style='margin: 0;'>Текущие файлы:</h3>
    <div class="row">
        <table class="c-table" id="tablefile">
            <tbody><?php echo $current_files; ?></tbody>
        </table>
    </div>
    <br>
    <h3 style='margin: 0;'>Загружаемые файлы:</h3>
    <a class="c-btn c-btn--warning c-btn--fullwidth" onclick="clickFile()" id="btnfile" style="width: auto">Выберите файлы</a>
    <input type="file" multiple="true" id="lfile" onchange="uploadFile(this)" hidden>
    <div class="row">
        <table class="c-table" id="tableloadfile">
            <tbody></tbody>
        </table>
    </div>
    <br>
    <div class="row" style="margin-top: 20px">
        <!-- Кнопка сохранить -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <a class="c-btn c-btn--success c-btn--fullwidth" onclick="clickSave('<?php echo $filepath; ?>')" id="btnfile">Загрузить</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>
        <!-- Кнопка отмена -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <a class="c-btn c-btn--secondary c-btn--fullwidth" href="?m=calculator">Отмена</a>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
} while(false);
?>
