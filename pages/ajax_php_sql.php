<?
// flag параметр
// 1 - Заказ Спиписок работ
// 2 - Смена влательца счета
// 3 - редактирование продукта для менеджеров
// 4 - сохранения продукта для менеджеров
// 5 - Удаление файла с сервера
// 6 - Добавление нового поставщика
// 7 - Список постащиков
// 8 - Список постащиков для select
// 9 - Список материалов
// 10
// 11
// 12
// 13
// 14
// 15
// 16
// 17
// 18
// 19
include "cron/PHPExcel/Classes/PHPExcel.php";
include "cron/PHPExcel/Classes/PHPExcel/Writer/Excel5.php";

function rus2translit($string)
{
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

function str2url($str)
{
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}

function copy_files($source, $res)
{
    $hendle = opendir($source); // открываем директорию
    while ($file = readdir($hendle)) {
        if (($file != ".") && ($file != "..")) {
            if (is_dir($source . "/" . $file) == true) {
                if (is_dir($res . "/" . $file) != true) // существует ли папка
                    mkdir($res . "/" . $file, 0777); // создаю папку
                copy_files($source . "/" . $file, $res . "/" . $file);
            } else {
                if (!copy($source . "/" . $file, $res . "/" . $file)) {
                    print ("при копировании файла $file произошла ошибка...<br>\n");
                }// end if copy
            }
        } // else $file == ..
    } // end while
    closedir($hendle);
}


function build_tree($cats, $parent_id, $only_parent = false)
{
    if (is_array($cats) and isset($cats[$parent_id])) {
        $tree = '<ul>';
        if ($only_parent == false) {
            foreach ($cats[$parent_id] as $cat) {
                $tree .= '<li>' . $cat['title'] . "&nbsp&nbsp&nbsp <a  class='glyphicon glyphicon-edit' onClick='_editklass(" . $cat['id'] . ")'></a> &nbsp&nbsp&nbsp<a  class='glyphicon glyphicon-trash' onClick='_delklass(" . $cat['id'] . ")'></a>";
                $tree .= build_tree($cats, $cat['id']);
                $tree .= '</li>';
            }
        } elseif (is_numeric($only_parent)) {
            $cat = $cats[$parent_id][$only_parent];
            $tree .= '<li>' . $cat['title'];
            $tree .= build_tree($cats, $cat['id']);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
    } else return null;
    return $tree;
}


function get_data()
{

    $query = "SELECT id, title, parent from kl_mat order by parent, title";
    $res = mysql_query($query);

    $data = array();
    while ($row = mysql_fetch_assoc($res)) {
        $data [] = $row;
    }
    return $data;
}

function print_three($data, $parent = 0)
{
    static $counter = 0;

    $counter += 5;
    for ($i = 0; $i < count($data); $i++) {
        if ($data [$i] ['parent'] == $parent) {
            echo "<option value = '" . $data [$i] ['id'] . "'>";
            for ($j = 5; $j < $counter; $j++) echo "&nbsp";
            echo $data [$i] ['title'] . "</option>";
            print_three($data, $data [$i] ['id']);
        }
    }
    $counter -= 5;
}


function chk_mat($size, $uz, $tiraj)
{
    // $size = '210*297';
    // $tiraj = 100;
    // $uz = 429;
    $kol = 0;
    $qr = "select m_size, m_kol_all from material_attr ma where ma.id_tree = " . $uz . " and m_kol_all > 0 and arh = 0";
    $result = mysql_query($qr) or die($qr);
    while ($row = mysql_fetch_row($result)) {

        $prod = explode("*", $size);
        $prod_size_1 = $prod[0];
        $prod_size_2 = $prod[1];

        $mat = explode("*", $row[0]);
        $mat_size_1 = $mat[0];
        $mat_size_2 = $mat[1];

        $x1 = (double)$mat_size_1 / (double)$prod_size_1;
        $x2 = (double)$mat_size_2 / (double)$prod_size_2;
        $x3 = (double)$mat_size_1 / (double)$prod_size_2;
        $x4 = (double)$mat_size_2 / (double)$prod_size_1;
        $x1 = floor($x1);
        $x3 = floor($x3);
        $x2 = floor($x2);
        $x4 = floor($x4);

        $y1 = $x1 * $x2;
        $y2 = $x3 * $x4;
        if (floor($y1) >= floor($y2)) {
            $pagekol = floor($y1);    //"К-во изд. на листе"
        } else {
            $pagekol = floor($y2);    //"К-во изд. на листе"
        }
        $kol += (int)$pagekol * (int)$row[1];

    }

    if ((int)$kol < (int)$tiraj) {
        echo '<span class="label label-danger">Нет в наличии материала!</span>';
    }

}


function track_post($track)
{
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    $homepage = file_get_contents('https://webservices.belpost.by/searchRu/' . $track, false, stream_context_create($arrContextOptions));

    $json = array();
    $pos = strpos($homepage, 'id="GridInfo"');
    if ($pos != FALSE) {
        $homepage = substr($homepage, $pos - 1);
        $pos1 = strpos($homepage, "</table>");

        $tb = substr($homepage, 1, $pos1);
        //echo "<table ".$tb ;
        $homepage = substr($homepage, $pos1 + 1);
        //echo "tb <table ".$tb;
        $tb = str_replace('<td><font face="Times New Roman" color="Black" size="4">', " ", $tb);
        $tb = str_replace('</font></td>', " | ", $tb);
        $tb = str_replace('<tr bgcolor="#8BE3FF">', " ", $tb);
        $tb = str_replace('<tr bgcolor="White">', " ", $tb);
        $tb = str_replace('</tr>', " ", $tb);
        $pos = strpos($tb, '<b>Офис</b></font></th>');
        $tb = substr($tb, $pos + 27);
        $tb = ltrim($tb);
        $tb = str_replace("\r\n", "", $tb);
        $tb = str_replace("\t", "", $tb);
        $tb = preg_replace("/ {2,}/", " ", $tb);
        $tb = str_replace(" | ", "|", $tb);

        $ast = explode("|", $tb);
        //echo count($ast) / 3;
        $i = 0;

        while ($i < (count($ast))) {
            $json[] = array(
                'date' => $ast[$i],
                'event' => $ast[++$i],
                'off' => $ast[++$i]
            );
            $i++;
        }

        array_pop($json);
    }

    $pos = strpos($homepage, 'id="GridInfo0"');
    if ($pos != FALSE) {
        $homepage = substr($homepage, $pos - 1);
        $pos1 = strpos($homepage, "</table>");

        $tb = substr($homepage, 1, $pos1);
        //echo "<table ".$tb ;
        $homepage = substr($homepage, $pos1 + 1);
        //echo "tb <table ".$tb;
        $tb = str_replace('<td><font face="Times New Roman" color="Black" size="4">', " ", $tb);
        $tb = str_replace('</font></td>', " | ", $tb);
        $tb = str_replace('<tr bgcolor="#8BE3FF">', " ", $tb);
        $tb = str_replace('<tr bgcolor="White">', " ", $tb);
        $tb = str_replace('</tr>', " ", $tb);
        $pos = strpos($tb, '<b>Офис</b></font></th>');
        $tb = substr($tb, $pos + 27);
        $tb = ltrim($tb);
        $tb = str_replace("\r\n", "", $tb);
        $tb = str_replace("\t", "", $tb);
        $tb = preg_replace("/ {2,}/", " ", $tb);
        $tb = str_replace(" | ", "|", $tb);

        $ast = explode("|", $tb);
        //echo count($ast) / 3;
        $i = 0;

        while ($i < (count($ast))) {
            $json[] = array(
                'date' => $ast[$i],
                'event' => $ast[++$i],
                'off' => $ast[++$i]
            );
            $i++;
        }
        array_pop($json);
    }
    $fruits = end($json);
    $return = $fruits['date'] . "|" . $fruits['event'];
    if (!empty($fruits['off']))
        $return .= "(" . $fruits['off'] . ")";
    //return $fruits['date']."|". $fruits['event']."(".$fruits['off'].")";
    return $return;
}


include 'firewall1.php';
session_start();
$login = $_SESSION['login'];
$query = "select user_per from users where user_login = '" . $login . "' LIMIT 1";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
    $admin = $row[0];
}

$flag = $_GET['flag'];
include 'db.php';
include 'pg/utility.php';
switch ($flag) {
    /*---------------------------1---------------------------*/
    case '1':
        switch ($admin) {
            case '4':
                $userStatus = "1,11,10,12,20";
                break;
            case '5':
                $userStatus = "10";
                break;
            case '6':
                $userStatus = "12";
                break;
            case '7':
                $userStatus = "11";
                break;

        }
        $query = " select DATE_FORMAT(op.dates_rdy, '%d.%m.%Y %H:%i') dt, 
	op.ORDER_ID ,
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
	op.p_names,  op.size, op.fast,op.cl_file, op.print_diz, op.view_diz, op.view_press, op.status,op.p_names, op.total, op.size,op.cshivka,op.template, press_diz, id,num_prod_ord ,
	(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
	(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm 
	from order_product op where status in (" . $srttt . ") AND NOT EXISTS (select lt.flags from lock_task lt where lt.id_prod = op.id AND lt.flags = 1)";


        $result = mysql_query($query) or die($query);
        $json = array();
        while ($row = mysql_fetch_row($result)) {
            $temp = explode("^", $row[16]);
            $eqq = "";
            $mat_eqq = "";
            for ($i = 0; $i < count($temp); $i++) {
                $z = $i + 1;
                $part = explode("|", $temp[$i]);
                if ($part[3] != "" and $part[3] != '0' and $part[3] != 'Выберите') {
                    $eqq .= $part[3] . " " . $part[4];
                }
                if ($part[6] != "" and $part[6] != '0') {
                    $mat = explode(":", $part[6]);
                    $mat_eqq .= $mat[0] . ' ' . $part[5] . ' Кол-во листов : ' . $mat[1];
                }
            }
        }

        $json[] = array(

            'date' => $row[0],
            'Number' => $row[1] . "_" . $row[19],
            'contr' => $row[2],
            'name_prod' => $row[4],
            'size' => $row[5],
            'meng' => $row[3],
            'prob' => $row[20],
            'comment' => $row[21],
            'id' => $row[18],
            'eqq' => $eqq,
            'mat_eqq' => $mat_eqq,
        );
        echo json_encode($json);
        break;
    /*---------------------------2---------------------------*/
    case '2':

        $id_acct = $_GET['id_acct'];
        $id_cl = $_GET['id_cl'];

        $query = "UPDATE orders SET CLIENT_ID = '{$id_cl}' WHERE  NUMBER = {$id_acct};";
        mysql_query($query) or die($query);
        break;
    /*---------------------------3---------------------------*/
    case '3':

        $id = $_GET['id'];


        $query = "select p_names,DATE_FORMAT(dates_rdy, '%Y-%m-%dT%H:%i'), print_diz, status,cl_file,press_diz from order_product where id = " . $id;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $p_names = $row[0];
            $dates_rdy = $row[1];
            $print_diz = $row[2];
            $status = $row[3];
            $cl_file = $row[4];
            $press_diz = $row[5];
        }

        //if ($admin == '4' OR $status == '0' OR $status == '20'){
        echo "<div class = 'row'>
	<div class='col-md-3'><label>Наименование: </label> </div><div class='col-md-9'><input type='hidden' id='id_prod_rev' name='id_prod_rev' value='$id'><input type='hidden' id='flag' name = 'flag' value='4'><input type='text' size = 100 name='p_names' id='p_names' value='$p_names'></div> 
	</div>
	<div class='row'>
	<div class='col-md-3'><label>Дата сдачи:</label></div>  
	<div class='col-md-9'><input type='datetime-local' name='p_dates_time' id='p_dates_time' value='$dates_rdy'></div>
	</div>";
        if ($admin == '4' or $status == '0' or $status == '20' or $status == '21' or $status == '1' or $status == '2') {
            echo "<div class = 'row'>
	<div class='col-md-3'><label>Передать: </label> </div>
	<div class='col-md-2'>	<select id = 'stats' name='stats'>
	<option value= '' selected></option>
	<option value= '12' >Печатник</option>
	<option value= '11' >Препресс</option>
	<option value= '1' >Цех</option>
	<option value= '10' >Дизайнер</option>";
            if ($admin == '4') {
                echo "<option value= '0' >Открыть</option><option value= '20' >Менеджер</option>";
            }
            echo "					</select>
	</div>
	</div>";
            echo '<div class="row">
	
	
	<div class="col-md-3"> <label >Файлы:</label></div>
	<div class="col-md-2"><label class="btn btn-default btn-file btn-sm">
	Выберете файлы...<input name="file[]" type="file" multiple="true" style="display: none;" id = "filea"/>	
	</label>	</div>
	<div class="col-md-1"> </div>
	</div>	';
        }

        if (is_dir('pg/' . $cl_file) and $cl_file != "") {

            echo "<b>Файлы для дизайнера:</b>";
            $files1 = scandir('pg/' . $cl_file);
            $list = '';
            $kol = 0;
            $list .= '<div class="row">
	<div class="col-md-4">&nbsp; </div>
	<div class="col-md-1"> </div>
	</div>';

            for ($i = 2; $i < count($files1); $i++) {
                $kol++;
                $list .= '<div class="row" id = "del_' . $kol . '"><div class="col-md-1">  </div>
	<div class="col-md-4"><label>' . $kol . '.</cl_file> ' . $files1[$i] . '  </div>
	<div class="col-md-5"> <a href="pg/' . $cl_file . '/' . $files1[$i] . '" download><span class = "pull-left"><i class="glyphicon glyphicon-floppy-save"></i></<span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                if ($admin == '4' or $status == '0' or $status == '20' or $status == '1' or $status == '2') {
                    $list .= '<a onClick="del_file(' . "'pg/" . $cl_file . '/' . $files1[$i] . "'" . ',' . "'del_" . $kol . "'" . ')"><span class = "pull-right"><i class="glyphicon  glyphicon-trash"></i></<span></a></div>
	</div>';
                }

            }

            echo $list;

        }
        if (is_dir('pg/' . $print_diz) and $print_diz != "") {

            echo "<b>Файлы для препресса:</b>";
            $files1 = scandir('pg/' . $print_diz);
            $list = '';
            $kol = 0;
            $list .= '<div class="row">
	<div class="col-md-4">&nbsp; </div>
	<div class="col-md-1"> </div>
	</div>';

            for ($i = 2; $i < count($files1); $i++) {
                $kol++;
                $list .= '<div class="row" id = "del_' . $kol . '"><div class="col-md-1">  </div>
	<div class="col-md-4"><label>' . $kol . '.</label> ' . $files1[$i] . '  </div>
	<div class="col-md-5"> <a href="pg/' . $print_diz . '/' . $files1[$i] . '" download><span class = "pull-left"><i class="glyphicon glyphicon-floppy-save"></i></<span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                if ($admin == '4' or $status == '0' or $status == '20' or $status == '1' or $status == '2') {
                    $list .= '<a onClick="del_file(' . "'pg/" . $print_diz . '/' . $files1[$i] . "'" . ',' . "'del_" . $kol . "'" . ')"><span class = "pull-right"><i class="glyphicon  glyphicon-trash"></i></<span></a></div>
	</div>';
                }

            }

            echo $list;

        }
        if (is_dir('pg/' . $press_diz) and $press_diz != "") {

            echo "<b>Файлы для печатника:</b>";
            $files1 = scandir('pg/' . $press_diz);
            $list = '';
            $kol = 0;
            $list .= '<div class="row">
	<div class="col-md-4">&nbsp; </div>
	<div class="col-md-1"> </div>
	</div>';

            for ($i = 2; $i < count($files1); $i++) {
                $kol++;
                $list .= '<div class="row" id = "del_' . $kol . '"><div class="col-md-1">  </div>
	<div class="col-md-4"><label>' . $kol . '.</label> ' . $files1[$i] . '  </div>
	<div class="col-md-5"> <a href="pg/' . $press_diz . '/' . $files1[$i] . '" download><span class = "pull-left"><i class="glyphicon glyphicon-floppy-save"></i></<span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                if ($admin == '4' or $status == '0' or $status == '20' or $status == '1' or $status == '2') {
                    $list .= '<a onClick="del_file(' . "'pg/" . $press_diz . '/' . $files1[$i] . "'" . ',' . "'del_" . $kol . "'" . ')"><span class = "pull-right"><i class="glyphicon  glyphicon-trash"></i></<span></a></div>
	</div>';
                }

            }

            echo $list;

        }


        break;
    /*---------------------------4---------------------------*/
    case '4':
        $id = $_GET['id'];
        $p_names = $_GET['p_names'];
        $p_dates_time = $_GET['p_dates_time'];
        $query = "UPDATE order_product SET dates_rdy = '{$p_dates_time}',p_names = '{$p_names}' WHERE  id = {$id};";
        mysql_query($query) or die($query);
        break;
    /*---------------------------5---------------------------*/
    case '5':
        echo $put;
        $put = $_GET['put'];
        unlink($put);
        break;
    /*---------------------------6---------------------------*/
    case '6':
        $name = $_GET['name'];
        $unp_firm = $_GET['unp_firm'];
        $id = $_GET['id'];
        if ($id == 0 or $id == '0') {
            $query = "INSERT INTO firms (FIRM_NAME,unp ) VALUES('{$name}','{$unp_firm}');";
        } else {
            $query = "UPDATE firms  SET FIRM_NAME = '{$name}' ,unp = '{$unp_firm}' WHERE  id = {$id};";
        }

        mysql_query($query) or die($query);
        break;
    /*---------------------------7---------------------------*/
    case '7':
        $json = array();
        $query = "select id,FIRM_NAME,unp from firms";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $json[] = array(
                'rev' => "<a onClick='edit_firm($row[0])'><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'id' => $row[0],
                'name' => $row[1],
                'unp' => $row[2],
            );
        }
        echo json_encode($json);
        break;
    /*---------------------------8---------------------------*/
    case '8':
        $list = '';
        $query = "select id,FIRM_NAME from firms  ORDER BY FIRM_NAME";
        $result = mysql_query($query) or die($query);
        echo '<select   class="myselect" data-live-search="true"  id = "cod_firm"  class="form-control" >
	<option value="0" selected>Выберите</option>';
        while ($row = mysql_fetch_row($result)) {
            echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
        }
        echo "</select>";
        break;
    /*---------------------------9---------------------------*/
    case '9':
        $json = array();
        $query = "select  m.ID, m2.MT_TYPE, m.M_NAME , m.M_UNIT , m.M_SIZE ,m.M_PRICE ,m.M_AVA , m.M_KOL_ALL, m.id_tree, (select km.title from kl_mat km where km.id = m.id_tree LIMIT 1) usel from material_attr m, material m2 where m.ID_M = m2.ID and m.arh = 0			";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_assoc($result)) {
            $inp = "<input type='checkbox' name='chjob' value='" . $row['ID'] . "'>";
            $json[] = array(

                'edit' => "<a onClick='edit_mat(" . $row['ID'] . ")'><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                'id' => $row['ID'],
                'MT_TYPE' => $row['MT_TYPE'],
                'M_NAME' => $row['M_NAME'],
                'M_UNIT' => $row['M_UNIT'],
                'M_SIZE' => $row['M_SIZE'],
                'M_PRICE' => $row['M_PRICE'],
                'M_AVA' => $row['M_AVA'],
                'M_KOL_ALL' => $row['M_KOL_ALL'],
                'YZEL' => "<a href='/pages/klass.php?idTree=" . intval($row['id_tree']) . "' target='_blank'>" . $row['usel'] . "</a>",
                'YZEL_EDIT' => "<a onclick='openSelectedNodeTree(" . $row['ID'] . ", " . intval($row['id_tree']) . ")'><i class='fa  fa-code-fork' aria-hidden='true'></i></a>",
                'CH' => $inp,
            );
        }
        echo json_encode($json);
        break;

    /*---------------------------10---------------------------*/
    case '10':
        $id1 = $_GET['id1'];

        $query = "select  m.ID, m2.MT_TYPE, m.M_NAME , m.M_UNIT , m.M_SIZE, m.M_KOL_ALL from material_attr m, material m2 where FIND_IN_SET(m.ID,'" . $id1 . "') AND m.ID_M = m2.ID and m.arh = 0	";
        $result = mysql_query($query) or die($query);
        $list_prod = "";
        $list_prod =
            "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-2'><label>ТИП</label></div> 
	<div class='col-md-3'><label>Наименование</label></div> 
	<div class='col-md-1'><label>Размер</label></div> 	
	<div class='col-md-1'><label>Ед. изм.</label></div> 	
	<div class='col-md-1'><label>Тек. кол-во</label></div> 
	<div class='col-md-1'><label></label></div> 
	</div>
	<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-2'></div>
	<div class='col-md-3'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	</div>";
        while ($row = mysql_fetch_row($result)) {

            $list_prod .=
                "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'><input type='checkbox' name='chjob2' checked value='" . $row[0] . "'></div> 
	<div class='col-md-3'>$row[1]</div> 
	<div class='col-md-2'>$row[2]</div> 
	<div class='col-md-1'>$row[4]</div>
	<div class='col-md-1'>$row[3]</div>
	<div class='col-md-1' id = 'total_$row[0]'>$row[5]</div>
	
	<div class='col-md-1'><input name='total$row[0]' id = 'total$row[0]' type='text' value='$total' size='7'/></div>
	</div>";
        }

        echo $list_prod;

        break;

    /*---------------------------11---------------------------*/
    case '11':
        $id1 = $_GET['id1'];
        $strr = explode("|", $id1);
        for ($z = 0; $z < count($strr); $z++) {
            $str2 = explode("^", $strr[$z]);
            $id_prod = $str2[0];
            $total = str_replace(",", ".", $str2[1]);
            $query = "UPDATE material_attr SET  M_KOL_ALL = " . $total . "  WHERE id = " . $id_prod;
            mysql_query($query) or die($query);


            $total1 = str_replace(",", ".", $str2[2]);
            $flags1 = $str2[3];
            $orderDate = date("Y-m-d H:i:s");
            $query = "INSERT INTO con_mater (id_mat , flags ,date ,total) VALUES('{$id_prod}','{$flags1}','{$orderDate}','{$total1}');";
            mysql_query($query) or die($query);

        }


        break;
    /*---------------------------12---------------------------*/
    case '12':

        $line = "<select  id = 'add_mat' name = 'add_mat' class='selectpicker' data-live-search='true'> <option value='' selected >Выберите материал</option>";
        $query = "select ma.id, ma.m_name, ma.m_size, m.MT_TYPE, ma.M_KOL_ALL from material m, material_attr ma where ma.ID_M = m.ID  and ma.arh = 0";
        $flag = '';
        $ko = 0;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            if ($flag != $row[3]) {
                if ($ko != 0) {
                    $line = $line . " </optgroup>";
                }
                $flag = $row[3];
                $line = $line . "<optgroup label='$row[3]'><option value='$row[0]'>$row[1] ($row[2]  КОЛ-ВО:$row[4])</option>";
                $ko = 1;
            } else {
                $line = $line . "<option value='$row[0]'>$row[1] ($row[2]  КОЛ-ВО:$row[4])</option>";
                $flag = $row[3];
                $ko = 1;
            }

        }
        $line = $line . "</select>";

        $line1 = "<select  id = 'type_mat' name = 'addMaterial'> <option value='' selected >Выберите материал</option>";
        $query = "select ID,MT_TYPE from material";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $line1 = $line1 . "<option value='$row[0]'>$row[1]</option>";
        }
        $line1 = $line1 . "</select>";

        $line2 = "<select id = 'izmMaterial'> <option value='' selected >Выберите ед. изм.</option>";
        $query = "select  * from units";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $line2 = $line2 . "<option value='" . $row[1] . "'> " . $row[1] . "</option>";
        }
        $line2 = $line2 . "</select>";
        echo '	
	<div id = "block2" style="display:none;">
	<div class="row">
	<div class="col-md-1">  </div>
	<div class="col-md-3"><label >Тип материала:</label> </div>
	<div class="col-md-3">' . $line1 . '</div>
	</div>
	<div class="row">
	<div class="col-md-1">  </div>
	<div class="col-md-3"><label>Имя материала:</label> </div>
	<div class="col-md-3"><input type="text" id = "nameMaterial" name="nameMaterial" size = "60"></div>
	</div>
	<div class="row">
	<div class="col-md-1">  </div>
	<div class="col-md-3"><label>Ед. измерения:</label> </div>
	<div class="col-md-3">' . $line2 . '</div>
	</div>
	<div class="row">
	<div class="col-md-1">  </div>
	<div class="col-md-3"><label>Размер, мм:</label> </div>
	<div class="col-md-3"><input type="text" id="sizeMaterial" name="sizeMaterial"></div>
	</div>
	<div class="row">
	<div class="col-md-1">  </div>
	<div class="col-md-3"><label>Стоимость за ед., BYN:</label> </div>
	<div class="col-md-3"><input type="text" id="priceMaterial" name="priceMaterial"></div>
	</div>
	
	</div>';

        break;

    /*---------------------------13---------------------------*/
    case '13':

        $cod_firm = $_GET['cod_firm'];
        $num_ttn = $_GET['num_ttn'];
        $date_ = $_GET['date_'];
        $all_sm = $_GET['all_sm'];
        $all_sm = str_replace(",", ".", $all_sm);
        $id1 = $_GET['id1'];
        $seria = $_GET['seria'];
        $query = "INSERT INTO TTN (num,cod_firm,dt,all_sum,seria) VALUES('{$num_ttn}','{$cod_firm}','{$date_}','{$all_sm}','{$seria}');";
        mysql_query($query) or die($query);
        $id_last = mysql_insert_id();
        echo $id_last;
        $id1 = $_GET['id1'];
        $strr = explode("|", $id1);
        for ($z = 0; $z < count($strr); $z++) {
            $str2 = explode("^", $strr[$z]);
            $id_prod = $str2[0];
            $units = $str2[1];
            $Name_ttn = $str2[4];
            $total = str_replace(",", ".", $str2[2]);
            $summ = str_replace(",", ".", $str2[3]);
            $total_ttn = str_replace(",", ".", $str2[5]);
            $cod_mat = $str2[6];
            $query = "INSERT INTO TTN_mater (id_TTN,id_mat,total,unit,sum_all,Name_ttn,total_ttn,cod_mat) VALUES('{$id_last}','{$id_prod}','{$total}','{$units}','{$summ}','{$Name_ttn}','{$total_ttn}' , '{$cod_mat}');";
            mysql_query($query) or die($query);
            $qw = "select M_KOL_ALL from material_attr where id = " . $id_prod;
            $res = mysql_query($qw) or die($qw);
            while ($row1 = mysql_fetch_row($res)) {
                $kol = $row1[0];
            }

            $all_total = $kol + $total;
            $query = "UPDATE material_attr SET  M_KOL_ALL = " . $all_total . "  WHERE id = " . $id_prod;
            mysql_query($query) or die($query);


        }
        break;
    /*---------------------------14---------------------------*/
    case '14':
        $json = array();
        $query = "select id ,DATE_FORMAT(dt, '%d.%m.%Y') dt, num, (select f.FIRM_NAME from firms f where f.id = cod_firm) f_name , all_sum, seria,cod_firm from ttn ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $json[] = array(
                'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                'rev' => "<a onClick='edit_ttn($row[0])'><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'id' => $row[0],
                'date' => $row[1],
                'num' => $row[2],
                'name_f' => $row[3],
                'sums' => $row[4],
                'seria' => $row[5],
                'eur' => "<a onclick = add_oplat('$row[0]','$row[6]','$row[2]')><span class = 'pull-right'><i class='glyphicon glyphicon-euro'></i></<span></a>",
            );
        }
        echo json_encode($json);
        break;
    /*---------------------------15---------------------------*/
    case '15':
        $idMaterial = $_GET['type_mat'];
        $nameMaterial = $_GET['nameMaterial'];
        $izmMaterial = $_GET['izmMaterial'];
        $sizeMaterial = $_GET['sizeMaterial'];
        $priceMaterial = $_GET['priceMaterial'];
        $tree_id = $_GET['tree_id'];
        $query = "select val from settings where id = 2; ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $kurs = $row[0];
        }
        $priceMaterial = str_replace(",", ".", $priceMaterial);
        $priceMaterial = ROUND($priceMaterial / $kurs, 2);
        $query = "INSERT INTO material_attr (ID_M, M_NAME, M_PRICE, M_SIZE, M_UNIT,M_TOL,id_tree)
	VALUES({$idMaterial},'{$nameMaterial}', '{$priceMaterial}','{$sizeMaterial}','{$izmMaterial}','-','{$tree_id}');";
        mysql_query($query) or die("0");
        echo mysql_insert_id();
        break;

    /*---------------------------16---------------------------*/
    case '16':
        $id = $_GET['id'];
        $str = "";
        $query = "select id, num,cod_firm,dt,all_sum, seria from TTN where id = " . $id;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $str = $str . $row[0] . "^" . $row[1] . "^" . $row[2] . "^" . $row[3] . "^" . $row[4] . "^" . $row[5] . "!";
        }
        $query = "select id_mat, (select M_NAME from material_attr m where m.id = id_mat  ) namess,  total,unit, sum_all,Name_ttn,total_ttn, cod_mat from TTN_mater where id_TTN =" . $id;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $str = $str . $row[0] . "^" . $row[1] . "^" . $row[2] . "^" . $row[3] . "^" . $row[4] . "^" . $row[5] . "^" . $row[6] . "^" . $row[7] . "|";
        }
        $str = substr($str, 0, -1);
        echo $str;

        break;

    /*---------------------------17---------------------------*/
    case '17':
        $id_rev = $_GET['id_rev'];

        /*Удаление предыдущих записей*/
        $query = "select id_mat,total from TTN_mater WHERE id_TTN = " . $id_rev;
        $res = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($res)) {
            $qw = "select M_KOL_ALL from material_attr where id = " . $row[0];
            $rs = mysql_query($qw) or die($qw);
            while ($row1 = mysql_fetch_row($rs)) {
                $kol = $row1[0];
            }
            $all_total = $kol - $row[1];
            $query1 = "UPDATE material_attr SET  M_KOL_ALL = " . $all_total . "  WHERE id = " . $row[0];
            mysql_query($query1) or die($query1);
        }

        $query = "DELETE FROM TTN_mater WHERE id_TTN = " . $id_rev;
        mysql_query($query) or die($query);
        /*-------------------------------------*/

        $cod_firm = $_GET['cod_firm'];
        $num_ttn = $_GET['num_ttn'];
        $date_ = $_GET['date_'];
        $all_sm = $_GET['all_sm'];
        $all_sm = str_replace(",", ".", $all_sm);
        $id1 = $_GET['id1'];
        $seria = $_GET['seria'];
        $query = "UPDATE TTN SET  num = '" . $num_ttn . "',cod_firm = '" . $cod_firm . "',dt = '" . $date_ . "',all_sum = '" . $all_sm . "',seria = '" . $seria . "'  WHERE id = " . $id_rev;
        mysql_query($query) or die($query);

        $id1 = $_GET['id1'];
        $strr = explode("|", $id1);
        for ($z = 0; $z < count($strr); $z++) {
            $str2 = explode("^", $strr[$z]);
            $id_prod = $str2[0];
            $units = $str2[1];
            $Name_ttn = $str2[4];
            $total = str_replace(",", ".", $str2[2]);
            $total_ttn = str_replace(",", ".", $str2[5]);
            $summ = str_replace(",", ".", $str2[3]);
            $cod_mat = $str2[6];
            $query = "INSERT INTO TTN_mater (id_TTN,id_mat,total,unit,sum_all,Name_ttn,total_ttn,cod_mat) VALUES('{$id_rev}','{$id_prod}','{$total}','{$units}','{$summ}','{$Name_ttn}','{$total_ttn}','{$cod_mat}');";
            mysql_query($query) or die($query);
            $qw = "select M_KOL_ALL from material_attr where id = " . $id_prod;
            $res = mysql_query($qw) or die($qw);
            while ($row1 = mysql_fetch_row($res)) {
                $kol = $row1[0];
            }

            $all_total = $kol + $total;
            $query = "UPDATE material_attr SET  M_KOL_ALL = " . $all_total . "  WHERE id = " . $id_prod;
            mysql_query($query) or die($query);


        }
        break;

    /*---------------------------18---------------------------*/
    case '18':
        $link = $_GET['link'];
        if (is_dir('pg/' . $link) and $link != "") {
            $files1 = scandir('pg/' . $link);
            $list = '';
            $kol = 0;
            $list .= '<div class="row">
			<div class="col-md-1">&nbsp; </div>
			<div class="col-md-7"> </div>
			<div class="col-md-1">&nbsp; </div>
			<div class="col-md-1"> </div>
			</div>';

            for ($i = 2; $i < count($files1); $i++) {
                $kol++;
                $nud = rand();
                $list .= '<div class="row" id = "del_' . $nud . '">
				<div class="col-md-1">  </div>
				<div class="col-md-7"><label>' . $kol . '.</label> ' . $files1[$i] . '  </div>
				<div class="col-md-1"> 
				<a href="' . $link . '/' . $files1[$i] . '" download><span class = "pull-left"><i class="glyphicon glyphicon-floppy-save"></i></<span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a onClick="del_file(' . "'" . $link . '/' . $files1[$i] . "'" . ',' . "'del_" . $nud . "'" . ')"><span class = "pull-right"><i class="glyphicon  glyphicon-trash"></i></<span></a>
				</div>
				<div class="col-md-1"> </div>
				</div>';
            }
            echo $list;
        }
        break;
    /*---------------------------19---------------------------*/
    case '19':

        $array_id = $_GET['array_id'];

        $query = "select ORDER_ID from order_product where FIND_IN_SET(id, '" . $array_id . "') LIMIT 1";
        $result = mysql_query($query) or die($query);
        if ($row = mysql_fetch_row($result)) {
            $insert_ID_ord = $row[0];
        }

        $query = "select ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, dates_rdy, fast, code_stat from order_product where FIND_IN_SET(id, '" . $array_id . "')";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {


            $query1 = "select num_prod_ord from order_product where ORDER_ID = " . $insert_ID_ord;
            $result1 = mysql_query($query1) or die($query1);
            while ($row1 = mysql_fetch_row($result1)) {
                $orderDate = date("Y-m-d H:i:s");
                if ((int)$max_num < (int)$row1[0]) {
                    $max_num = (int)$row1[0];
                }
            }


            $name_prod1 = str_replace(' ', '_', $row[10]);
            $name_prod1 = str_replace('\\', '_', $name_prod1);
            $name_prod1 = substr($name_prod1, 0, 25);
            $name_prod1 = str2url($name_prod1) . "_" . rand();


            $name = $row[0];

            $name_dir = "files/prod/$name";
            if (is_dir($name_dir) == FALSE) {
                mkdir($name_dir, 0777);
            }
            $name = $name . '/' . $name_prod1;
            $name_dir = "files/prod/$name/";
            if (is_dir($name_dir) == FALSE) {
                mkdir($name_dir, 0777);
            }
            $name_dir1 = "files/prod/$name/client";
            $name_r1 = "\\files\prod\\" . $name . "\\client";
            if (is_dir($name_dir1) == FALSE) {
                mkdir($name_dir1, 0777);
            }
            $name_dir2 = "files/prod/$name/diz";
            $name_r2 = "\\files\prod\\" . $name . "\\diz";
            if (is_dir($name_dir2) == FALSE) {
                mkdir($name_dir2, 0777);
            }
            $name_dir3 = "files/prod/$name/press";
            $name_r3 = "\\files\prod\\" . $name . "\\press";
            if (is_dir($name_dir3) == FALSE) {
                mkdir($name_dir3, 0777);
            }

            copy_files($row[13], $name_dir2);

            $max_num++;
            $query1 = "INSERT INTO order_product (ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, dates_rdy, fast,num_prod_ord,add_date, code_stat) VALUES(	'{$insert_ID_ord}','{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$row[5]}','{$row[6]}','{$row[7]}','{$row[8]}','{$row[9]}','{$row[10]}','{$row[11]}','{$name_dir3}','{$name_dir2}','{$row[14]}','{$row[15]}','{$row[16]}','4','{$row[18]}','{$name_dir1}','{$row[20]}','{$row[21]}','{$row[22]}','{$max_num}' , '{$orderDate}','{$row[23]}');";
            mysql_query($query1) or die($query1);
        }
        mysql_close($connection);
        break;
    /*---------------------------20---------------------------*/
    case '20':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','1');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='1' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;
    /*---------------------------21---------------------------*/
    case '21':

        $id = $_GET['id'];
        $query = "DELETE FROM material_attr WHERE  ID=" . $id;
        mysql_query($query) or die($query);

        break;
    /*---------------------------22---------------------------*/
    case '22':
        $id = $_GET['id'];
        $qr2 = "select (select ma.M_NAME from material_attr ma where ma.ID = id_mat) mae, total, unit, sum_all, (select ma.M_size from material_attr ma where ma.ID = id_mat) size from TTN_mater where id_ttn = " . $id;
        $list_prod = "";
        $iy = 0;
        $rs = mysql_query($qr2) or die($qr2);
        $list_prod =
            "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-4'>Наименование</div> 
	<div class='col-md-1'>Кол-во</div> 
	<div class='col-md-1'>Размер</div> 
	<div class='col-md-1'>Ед. изм.</div> 
	<div class='col-md-1'>Сумма</div>
	</div>";
        while ($rw = mysql_fetch_row($rs)) {
            $iy++;
            $list_prod = $list_prod .
                "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'>" . $iy . ".</div> 
	<div class='col-md-4'>" . $rw[0] . "</div> 
	<div class='col-md-1'>" . $rw[1] . "</div> 
	<div class='col-md-1'>" . $rw[4] . "</div>
	<div class='col-md-1'>" . $rw[2] . "</div> 
	<div class='col-md-1'>" . $rw[3] . "</div>
	</div>";
        }
        echo $list_prod;


        break;

    /*---------------------------23---------------------------*/
    case '23':
        //Выбираем данные из БД
        $result = mysql_query("SELECT * FROM  kl_mat order by parent, title");
        //Если в базе данных есть записи, формируем массив
        if (mysql_num_rows($result) > 0) {
            $cats = array();
            //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
            while ($cat = mysql_fetch_assoc($result)) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent']][$cat['id']] = $cat;
            }
        }

        echo build_tree($cats, 0);

        break;


    /*---------------------------24---------------------------*/
    case '24':
        $d = get_data();
        echo "<select id='uz_tree'><option value = '0'></option>";
        print_three($d);
        echo "</select>";
        break;
    /*---------------------------25---------------------------*/
    case '25':
        $name = $_GET['name'];
        $uz_num = $_GET['uz_num'];
        $chek = $_GET['chek'];
        $query = "INSERT INTO kl_mat (title,parent,flags) VALUES('{$name}','{$uz_num}','{$chek}');";
        mysql_query($query) or die($query);
        $last_id = mysql_insert_id();
        $result = mysql_query("SELECT * FROM  kl_mat order by parent, title");
        //Если в базе данных есть записи, формируем массив
        if (mysql_num_rows($result) > 0) {
            $cats = array();
            //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
            while ($cat = mysql_fetch_assoc($result)) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent']][$cat['id']] = $cat;
            }
        }

        echo build_tree($cats, 0);

        $option = $_GET['option'];


        for ($i = 0; $i < count($option); $i++) {
            $query = "UPDATE material_attr SET id_tree =  " . $last_id . " WHERE id=" . $option[$i];
            mysql_query($query) or die($query);
        }


        break;

    /*---------------------------26---------------------------*/
    case '26':
        $name = $_GET['name'];
        $uz_num = $_GET['uz_num'];
        $id_tree = $_GET['id_tree'];
        $chek = $_GET['chek'];
        $query = "UPDATE kl_mat SET title = '{$name}', parent = '{$uz_num}', flags = '{$chek}' WHERE  ID=" . $id_tree;
        mysql_query($query) or die($query);

        $result = mysql_query("SELECT * FROM  kl_mat order by parent, title");
        //Если в базе данных есть записи, формируем массив
        if (mysql_num_rows($result) > 0) {
            $cats = array();
            //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
            while ($cat = mysql_fetch_assoc($result)) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent']][$cat['id']] = $cat;
            }
        }
        echo build_tree($cats, 0);

        $option = $_GET['option'];

        for ($i = 0; $i < count($option); $i++) {
            $query = "UPDATE material_attr SET id_tree = " . $id_tree . " WHERE id=" . $option[$i];
            mysql_query($query) or die($query);
        }


        break;
    /*---------------------------27---------------------------*/
    case '27':

        $id_tree = $_GET['id_tree'];

        $query = "SELECT title,parent,flags FROM  kl_mat WHERE  ID=" . $id_tree . " LIMIT 1";
        $result = mysql_query($query);


        //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
        while ($cat = mysql_fetch_assoc($result)) {
            echo $cat['title'] . "`" . $cat['parent'] . "`" . $cat['flags'];
        }

        break;

    /*---------------------------28---------------------------*/
    case '28':

        $id_tree = $_GET['id_tree'];

        $query = "DELETE FROM kl_mat  WHERE  ID=" . $id_tree;
        mysql_query($query) or die($query);

        $result = mysql_query("SELECT * FROM  kl_mat order by parent, title");
        //Если в базе данных есть записи, формируем массив
        if (mysql_num_rows($result) > 0) {
            $cats = array();
            //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
            while ($cat = mysql_fetch_assoc($result)) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent']][$cat['id']] = $cat;
            }
        }
        echo build_tree($cats, 0);

        break;

    /*---------------------------29---------------------------*/
    case '29':
        $query = "select MT_TYPE, ma.M_NAME, ma.ID, ma.M_PRICE, ma.M_SIZE from material m, material_attr ma where ma.ID_M = m.ID and ma.arh = 0 and ma.M_NAME is not null AND (ma.id_tree is null OR  ma.id_tree = 0) ORDER BY MT_TYPE , ma.M_NAME;";
        $result = mysql_query($query) or die("Query failed");
        $prod_idd = explode(",", $ca);
        $flag = '';
        $ko = 0;
        while ($row = mysql_fetch_row($result)) {
            if ($flag != $row[0]) {
                if ($ko != 0) {
                    print " </optgroup>";
                }
                $flagss = 0;
                $flag = $row[0];
                $ko = 1;
                for ($z = 0; $z < count($prod_idd); $z++) {
                    if ($row[2] == $prod_idd[$z]) {
                        print "<optgroup label='$row[0]'><option value='$row[2]' selected>" . $row[1] . ' ' . $row[4] . "</option>";
                        $flagss = 1;
                    }
                }
                if ($flagss == 0) {
                    print "<optgroup label='$row[0]'><option value='$row[2]'>" . $row[1] . ' ' . $row[4] . "</option>";
                }


            } else {
                $flagss = 0;
                $flag = $row[0];
                $ko = 1;
                for ($z = 0; $z < count($prod_idd); $z++) {
                    if ($row[2] == $prod_idd[$z]) {
                        print "<option value='$row[2]' selected>" . $row[1] . ' ' . $row[4] . "</option>";
                        $flagss = 1;
                    }
                }
                if ($flagss == 0) {
                    print "<option value='$row[2]'>" . $row[1] . ' ' . $row[4] . "</option>";
                }


            }
        }
        break;

    /*---------------------------30---------------------------*/
    case '30':
        $id = $_GET['id'];
        echo "<div class='row'><div class='col-md-1'></div><div class='col-md-11'> &nbsp;</div></div>";
        echo "<div class='row'><div class='col-md-1'>Выбраны</div><div class='col-md-11'>&nbsp; </div></div>";
        $query = "select  ma.M_NAME, ma.ID, ma.M_SIZE from  material_attr ma where  ma.id_tree = " . $id . " ORDER BY  ma.M_NAME";

        $result = mysql_query($query) or die($result);
        $E = 0;
        while ($row = mysql_fetch_row($result)) {
            $E++;
            echo "<div class='row' id = '" . $row[1] . "'><div class='col-md-1'>" . $E . "</div><div class='col-md-7'>" . $row[0] . " " . $row[2] . "</div><div class='col-md-1'><a  class='glyphicon glyphicon-trash' onClick='_delgoods(" . $row[1] . ")'></a></div></div>";
        }
        break;


    /*---------------------------31---------------------------*/
    case '31':


        $query = "SELECT id id,  title name, title text, parent parent_id  FROM kl_mat ORDER BY title  ";
        $result = mysql_query($query) or die($query);
        $data = array();
        while ($row = mysql_fetch_array($result)) {

            // $d = $row["parent_id"];

            // if ($d == '0' OR $d == 0){
            // $d = '#';
            // }


            $data[] = array(
                'id' => $row["id"],
                'parent_id' => $row["parent_id"],
                'text' => $row["text"],

            );


        }

        $itemsByReference = array();
        $item = array();
        // Build array of item references:
        foreach ($data as $key => &$item) {

            $itemsByReference[$item['id']] = &$item;
            // Children array:
            $itemsByReference[$item['id']]['children'] = array();
            // Empty data class (so that json_encode adds "data: {}" )
            $itemsByReference[$item['id']]['data'] = new StdClass();
        }

        // Set items as children of the relevant parent item.
        foreach ($data as $key => &$item)
            if ($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
                $itemsByReference [$item['parent_id']]['children'][] = &$item;

        // Remove items that were added to parents elsewhere:
        foreach ($data as $key => &$item) {
            if ($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
                unset($data[$key]);
        }

        // Encode:

        //	print_r(array_shift($data));
        echo json_encode(array_shift($data));


        break;


    /*---------------------------32---------------------------*/
    case '32':


        $id = $_GET['id'];


        $query = "UPDATE material_attr SET id_tree = '0' WHERE  ID=" . $id;
        mysql_query($query) or die($query);
        echo $query;

        $result = mysql_query("SELECT * FROM  kl_mat order by parent, title");
        //Если в базе данных есть записи, формируем массив
        if (mysql_num_rows($result) > 0) {
            $cats = array();
            //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
            while ($cat = mysql_fetch_assoc($result)) {
                $cats_ID[$cat['id']][] = $cat;
                $cats[$cat['parent']][$cat['id']] = $cat;
            }
        }
        echo build_tree($cats, 0);


        break;


    /*---------------------------33---------------------------*/
    case '33':
        echo $str_id . "<br>";
        $str_id = $_GET['str_id'];
        $part = explode("|", $str_id);
        for ($i = 0; $i < count($part); $i++) {
            $par = explode(",", $part[$i]);

            $query = "select ORDER_ID from  order_product  WHERE  ID=" . $par[0];
            $result = mysql_query($query) or die($query);
            while ($row = mysql_fetch_row($result)) {
                $old = $row[0];
            }
            $query = "UPDATE order_product SET ORDER_ID = '{$par[1]}' WHERE  ID=" . $par[0];
            mysql_query($query) or die($query);
            $orderDate = date("Y-m-d H:i:s");
            $msg = "Перемещение продукта " . $par[0] . " В счет " . $par[1] . " Старый счет " . $old;
            $query = "INSERT INTO logs (users,date_tm,table_,log ) VALUES('{$login}','{$orderDate}','order_product','{$msg }');";
            mysql_query($query) or die($query);


        }

        break;
    /*---------------------------34---------------------------*/
    case '34':

        $link = $_GET['link'];
        if (is_dir('pg/' . $link) and $link != "") {

            $files1 = scandir('pg/' . $link);
            $list = '';
            $kol = 0;
            $list .= '<div class="row">
	<div class="col-md-1">&nbsp; </div>
	<div class="col-md-7"> </div>
	<div class="col-md-1">&nbsp; </div>
	<div class="col-md-1"> </div>
	</div>';

            for ($i = 2; $i < count($files1); $i++) {
                $kol++;
                $nud = rand();
                $list .= '<div class="row" id = "del_' . $nud . '">
	<div class="col-md-1">  </div>
	<div class="col-md-7"><label>' . $kol . '.</label> ' . $files1[$i] . '  </div>
	<div class="col-md-1"> 
	<a href="pg/' . $link . '/' . $files1[$i] . '" download><span class = "pull-left"><i class="glyphicon glyphicon-floppy-save"></i></<span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a onClick="del_file(' . "'pg/" . $link . '/' . $files1[$i] . "'" . ',' . "'del_" . $nud . "'" . ')"><span class = "pull-right"><i class="glyphicon  glyphicon-trash"></i></<span></a>
	</div>
	<div class="col-md-1"> </div>
	</div>';

            }
            echo $list;
        }
        break;

    /*---------------------------35---------------------------*/
    case '35':

        $size = $_GET['size'];
        $tiraj = $_GET['tiraj'];
        $uz = $_GET['uz'];

        chk_mat($size, $uz, $tiraj);
        break;



    /*---------------------------36---------------------------*/
    // Оплаты
    case '36':
        /*$query="select  o.ID, (select c.CLIENT_NAME FROM clients c where c.ID =  o.CLIENT_ID) name , o.ORDER_NUM , o.OST_SUM , o.ALL_SUM, DATE_FORMAT(o.DATE_, '%d.%m.%Y')  , o.view_opl, o.Comments,
	(select f.FIRM_NAME FROM firms f where f.id =  o.CLIENT_ID) name1 from oplati o";*/

        $query = "SELECT o.ID, 
					(SELECT c.CLIENT_NAME FROM clients c WHERE c.ID = o.CLIENT_ID) name, 
					o.ORDER_NUM, 
					o.OST_SUM, 
					o.ALL_SUM, 
					DATE_FORMAT(o.DATE_, '%d.%m.%Y') dt, 
					o.view_opl, 
					o.Comments, 
					(SELECT f.FIRM_NAME FROM firms f WHERE f.id = o.CLIENT_ID) name1
				FROM oplati o
				WHERE DATE_FORMAT(o.DATE_, '%Y') + 2 > DATE_FORMAT(CURDATE(), '%Y')";

        $result = mysql_query($query) or die($query);
        $json = array();
        while ($row = mysql_fetch_row($result)) {

            $td2 = '';
            $td7 = '';
            if ($row[6] == 5) {

                $td2 = $row[8];
            } else {
                $td2 = $row[1];
            }


            switch ($row[6]) {
                case 0:
                    $td7 = '';
                    break;
                case 1:
                    $td7 = 'касса';
                    break;
                case 2:
                    $td7 = 'терминал';
                    break;
                case 3:
                    $td7 = 'безнал';
                    break;
                case 4:
                    $td7 = 'наличные';
                    break;
                case 5:
                    $td7 = 'расчет с поставщиками';
                    break;
                default:
                    $td7 = '';
                    break;
            }


            $json[] = array(
                'flags' => '<a onclick = ' . '"' . "edit_opl('$row[0]', '$row[6]')" . '"' . "><span class='pull-right'><i class='glyphicon glyphicon-pencil'></i></<span></a>",
                'td2' => $td2,
                'td3' => $row[2],
                'td4' => $row[3],
                'td5' => $row[4],
                'td6' => $row[5],
                'td7' => $td7,
                'td8' => $row[7]
            );
        }
        echo json_encode($json);
        break;

    /*---------------------------37---------------------------*/
    // TTN
    case '37':
        $query = "SELECT DATE_FORMAT(tn_list_par.dates_, '%d.%m.%Y') dates_,
						tn_list_par.num_tm,
						tn_list_par.order_id,
						users.USER_FIO,
						tn_list_par.del,
						tn_list_par.exp_1c,
						tn_list_par.`type`,
						clients.UNP,
						tn_list_par.ID,
						tn_list_par.summ,
						orders.parent_company
			FROM tn_list_par
			INNER JOIN orders ON tn_list_par.order_id=orders.NUMBER
			INNER JOIN users ON orders.USER_ID=users.USER_LOGIN
			INNER JOIN clients ON orders.CLIENT_ID=clients.ID";

        $result = mysql_query($query) or die($query);
        $json = array();
        while ($row = mysql_fetch_row($result)) {
            $vig = ($row[4] == '1') ? "Да" : "НЕТ";
            $del = ($row[5] == '1') ? "Да" : "НЕТ";
            $type = ($row[10] == 2) ? $row[6] . '_m' : $row[6];
            $json[] = array(
                'flags' => "<a onClick=" . '"' . "view_tn($row[8], '$type')" . '"' . "><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'td2' => $row[0],
                'td3' => $row[1],
                'td1' => $row[2],
                'td4' => $row[7],
                'td5' => $row[3],
                'td6' => $vig,
                'td7' => $del,
                'td8' => $row[6],
                'td9' => $row[9]
            );
        }
        echo json_encode($json);
        break;


    /*---------------------------38---------------------------*/
    case '38':
        $id_cl = $_GET['id_cl'];

        $query = "UPDATE users SET cl_id= '{$id_cl}'  WHERE USER_LOGIN ='{$login}'";
        mysql_query($query) or die($query);


        $query = "SELECT o.NUMBER,  DATE_FORMAT(o.DATE_OR, '%d.%m.%Y'),(select user_fio from users where user_login = o.user_id) user_name, 
	o.STATUS_ID,(select client_name from clients where id = o.client_id)  client_name,
	(select email from clients where id = o.client_id)  client_mail, o.client_id,
	(select UNP from clients where id = o.client_id)  client_unp	, 
	(select ROUND(sum(opl.ALL_SUM),2) from oplati opl where  opl.ORDER_NUM = o.NUMBER  and opl.id > 0 group by opl.ORDER_NUM) opl,
	(select ROUND(sum(tn.summ),2) from tn_list_par tn where  tn.order_id = o.NUMBER and tn.del = 0 and num_tm <> 0 group by tn.order_id) ttn,
	(select ROUND(SUM( ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) sum from order_product op where op.order_id = o.NUMBER ORDER BY op.order_id) sumss	,
	DATE_FORMAT(o.DATE_OR, '%Y-%m-%d')
	FROM (SELECT * FROM orders WHERE CLIENT_ID = " . $id_cl . ") o ";

        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $aq = $row[8];
            $tn_sum = $row[9];
            $summ1 = $row[10];

            $opl = "";
            if ($admin == '4' or $admin == '2' or $login == '026' or $login == '030' or $login == '028' or $login == '033' or $login == '032') {
                $opl = "<a onclick = add_oplat('$row[0]','$row[6]')><span class = 'pull-right'><i class='glyphicon glyphicon-euro'></i></<span></a>";
            }

            $json[] = array(
                'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                'edit' => "<a onClick='acct($row[0])'><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'dates' => $row[1],
                'id' => $row[0],
                'names' => $row[4],
                'unp' => $row[7],
                'mng' => $row[2],
                'sum1' => $summ1,
                'sum2' => number_format($tn_sum, 2, '.', ' '),
                'TM' => $list_prod,
                'opl' => number_format($aq, 2, '.', ' '),
                'opl_add' => $opl,
                'info' => "<a onClick='_smena_cl($row[0])'><button type= 'button' class='btn btn-info btn-circle'><span class='glyphicon glyphicon-user    '></span></button></a>" . '&nbsp;&nbsp;<button type= "button" class="btn btn-info btn-circle" onClick="_smena_dt(' . "'$row[0]','$row[11]'" . ' )"><span class="glyphicon glyphicon-calendar"></span></button>',

            );
        }
        echo json_encode($json);
        break;

    /*---------------------------39---------------------------*/
    case '39':
        $id_cl = $_GET['id_cl'];
        $query = "select 
	op.ORDER_ID , 
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, 
	(select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	op.p_names,	
	(IF(op.units <> 'тыс.шт.', op.total, op.total * 1000 )) total,
	op.status,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	ROUND((ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) SUMS,
	( select (select u.USER_FIO from users u where u.user_login = lt.users) from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) dd,
	op.num_prod_ord,
	op.id,
	DATE_FORMAT(o.dt, '%d.%m.%Y')
	from (SELECT number,DATE_FORMAT(o.DATE_OR, '%Y-%m-%d') dt FROM orders o where CLIENT_ID = " . $id_cl . ") o , order_product op where op.ORDER_ID = o.number  ";

        if ($id_cl == '-1') {
            $query = "select 
	op.ORDER_ID , 
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, 
	(select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	op.p_names,	
	(IF(op.units <> 'тыс.шт.', op.total, op.total * 1000 )) total,
	op.status,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	ROUND((ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) SUMS,
	( select (select u.USER_FIO from users u where u.user_login = lt.users) from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) dd,
	op.num_prod_ord,
	op.id,
	DATE_FORMAT(o.dt, '%d.%m.%Y')
	from (SELECT number,DATE_FORMAT(o.DATE_OR, '%Y-%m-%d') dt FROM orders o where USER_ID = '" . $login . "') o , order_product op where op.ORDER_ID = o.number  ";
        }


        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $list_prod = "";
            switch ($row[5]) {
                case '4':
                    $list_prod = "<span class='label label-danger '>Брак/Отказ</span>";
                    break;
                case '20':
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case '0':
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case '1':
                    $list_prod = "<span class='label label-info '>в цеху</span>";
                    break;
                case '2':
                    $list_prod = " <span class='label label-success'>Готово</span>";
                    break;
                case '3':
                    $list_prod = " <span class='label label-success'>Отдано</span>";
                    break;
                case '21':
                    $list_prod = " <span class='label label-danger'>Cогласование с клиентом</span>";
                    break;
                case '10':
                    if ($row[7] == '1') {
                        $list_prod = " <span class='label label-primary'>Дизайн " . $row[8] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание дизайна</span>";
                        break;
                    }
                    break;
                case '11':
                    if ($row[7] == '1') {
                        $list_prod = "<span class='label label-primary '>Препресс " . $row[8] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание препресса</span>";
                        break;
                    }
                case '12':
                    if ($row[7] == '1') {
                        $list_prod = " <span class='label label-info'>Печатает " . $row[8] . "</span>";
                        break;
                    } else {
                        $list_prod = " <span class='label label-info'>Ожидание печати</span>";
                        break;
                    }
                default:
                    $list_prod = "";
                    break;
            }
            $json[] = array(
                'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                'dt' => $row[11],
                'name' => $row[1],
                'col1' => "<a href = '/pages/pg/_addAcct.php?id=" . $row[0] . "'>" . $row[0] . '_' . $row[9] . '</a>',
                'col3' => $row[3],
                'col4' => $list_prod,
                'col5' => $row[4],
                'col6' => $row[7],
                'id_prod' => $row[10],
                'copy' => "<input type='checkbox' class='chcopy_' name='chcopy_p' value='" . $row[10] . "'>"

            );
        }

        //.'_'.$row[5]
        echo json_encode($json);
        break;


    /*---------------------------40---------------------------*/
    case '40':
        $id_cl = $_GET['id_cl'];
        $query = "select  o.ID, (select c.CLIENT_NAME FROM clients c where c.ID =  o.CLIENT_ID) name , o.ORDER_NUM , o.OST_SUM , o.ALL_SUM, DATE_FORMAT(o.DATE_, '%d.%m.%Y') , o.view_opl, o.Comments, 
	(select f.FIRM_NAME FROM firms f where f.id =  o.CLIENT_ID) name1 from oplati o where o.CLIENT_ID =" . $id_cl;

        $result = mysql_query($query) or die($query);
        $json = array();
        while ($row = mysql_fetch_row($result)) {

            $td2 = '';
            $td7 = '';
            if ($row[6] == 5) {

                $td2 = $row[8];
            } else {
                $td2 = $row[1];
            }


            switch ($row[6]) {
                case 0:
                    $td7 = '';
                    break;
                case 1:
                    $td7 = 'касса';
                    break;
                case 2:
                    $td7 = 'терминал';
                    break;
                case 3:
                    $td7 = 'безнал';
                    break;
                case 4:
                    $td7 = 'наличные';
                    break;
                case 5:
                    $td7 = 'расчет с поставщиками';
                    break;
                default:
                    $td7 = '';
                    break;
            }


            $json[] = array(
                'flags' => '',
                'td2' => $td2,
                'td3' => "<a href = '/pages/pg/_addAcct.php?id=" . $row[2] . "'>" . $row[2] . '</a>',
                'td4' => $row[3],
                'td5' => $row[4],
                'td6' => $row[5],
                'td7' => $td7,
                'td8' => $row[7]

            );
        }
        echo json_encode($json);
        break;


    /*---------------------------41---------------------------*/
    // TTN
    case '41':

        $id_cl = $_GET['id_cl'];
        $query = "select 
	DATE_FORMAT(dates_, '%d.%m.%Y') dates_, 
	num_tm, 
	order_id, 
	(select user_fio from users where user_login = user_log), 
	del, 
	exp_1c, 
	type ,
	(select c.UNP from clients c where c.ID = (select client_id from orders o where o.NUMBER = order_id)) unp, 
	id, 
	summ 
	from (SELECT number FROM orders o where CLIENT_ID = " . $id_cl . ") orde,  tn_list_par tn  where tn.order_id =  orde.number";

        $result = mysql_query($query) or die($query);
        $json = array();
        while ($row = mysql_fetch_row($result)) {

            if ($row[4] == '1') {
                $vig = "Да";
            } else {
                $vig = "НЕТ";
            }
            if ($row[5] == '1') {
                $del = "Да";
            } else {
                $del = "НЕТ";
            }
            $json[] = array(
                'flags' => "<a onClick=" . '"' . "view_tn($row[8], '$row[6]')" . '"' . "><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                'td2' => $row[0],
                'td3' => $row[1],
                'td1' => "<a href = '/pages/pg/_addAcct.php?id=" . $row[2] . "'>" . $row[2] . '</a>',
                'td4' => $row[7],
                'td5' => $row[3],
                'td6' => $vig,
                'td7' => $del,
                'td8' => $row[6],
                'td9' => $row[9]
            );
        }
        echo json_encode($json);
        break;


    /*---------------------------42---------------------------*/
    // TTN
    case '42':
        $id = $_GET['id'];
        $qr2 = "select op.p_names, op.total, op.price, op.summ, op.units, op.id,op.status, 
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) lk from order_product op where op.order_id = " . $id;

        $list_prod = "";
        $iy = 0;
        $rs = mysql_query($qr2) or die($qr2);
        $list_prod = "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-3'>Наименование</div> 
	<div class='col-md-1'>Кол-во</div> 
	<div class='col-md-1'>Ед. изм.</div> 
	<div class='col-md-1'>Цена</div>
	<div class='col-md-1'>Сумма</div>
	<div class='col-md-1'>Статус</div>
	</div>";
        $summ1 = 0;
        while ($rw = mysql_fetch_row($rs)) {
            $nds_ = 1.2;
            $total = 0;
            $summ = 0;
            $summ_no_nds = 0;
            $sum_nds = 0;
            $price = 0;
            $total = $rw[1];
            $summ = $rw[3];
            if ($total != 0) {
                $price = round($rw[2], 2) / $nds_;
                $price = round($price, 2);
                $summ = $total * round($price, 2) * $nds_;
                $summ = round($summ, 2);
                $summ_no_nds = round($price * $total, 2);
                $sum_nds = $summ - $summ_no_nds;
                $summ_no_nds = round($summ_no_nds, 2);
                $sum_nds = round($sum_nds, 2);
                $summ = round($summ, 2);
                $summ1 = $summ1 + $summ;
            }
            $iy++;
            $list_prod = $list_prod . "<div class = 'row'>
	<div class='col-md-1'><input type='checkbox' name='chDel' value='" . $rw[5] . "'></div> 
	<div class='col-md-3'>" . $iy . ". " . $rw[0] . "</div> 
	<div class='col-md-1'>" . $rw[1] . "</div> 
	<div class='col-md-1'>" . $rw[4] . "</div> 
	<div class='col-md-1'>" . number_format($price, 2, '.', ' ') . "</div>
	<div class='col-md-1'>" . number_format($summ, 2, '.', ' ') . "</div>
	<div class='col-md-1'>";
            switch ($rw[6]) {
                case 4:
                    $list_prod = $list_prod . "<span class='label label-danger '>Брак/Отказ</span>";
                    break;
                case 21:
                    $list_prod = $list_prod . " <span class='label label-danger'>Cогласование с клиентом</span>";
                    break;
                //case 0: $list_prod  = $list_prod. "<span class='label label-danger '>Созданный</span>"; break;
                case 3:
                    $list_prod = $list_prod . "<span class='label label-success '>Отдано заказчику</span>";
                    break;
                case 20:
                    $list_prod = $list_prod . "<span class='label label-danger '>Возврат</span>";
                    break;
                case 1:
                    $list_prod = $list_prod . "<span class='label label-info '>в цеху</span>";
                    break;
                case 2:
                    $list_prod = $list_prod . " <span class='label label-success'>Готово</span>";
                    break;
                case 10:
                    $list_prod = $list_prod . " <span class='label label-primary'>Дизайн</span>";
                    break;
                case 11:
                    if ($rw[7] == '1') {
                        $list_prod = $list_prod . "<span class='label label-primary '>Препресс</span>";
                        break;
                    } else {
                        if ($rw[7] == '0') {
                            $list_prod = $list_prod . "<span class='label label-primary '>Ожидание препресса</span>";
                            break;
                        } else {
                            $list_prod = $list_prod . "";
                        }
                    }
                case 12:
                    if ($rw[7] == '1') {
                        $list_prod = $list_prod . " <span class='label label-info'>Печатается</span>";
                        break;
                    } else {
                        if ($rw[7] == '0') {
                            $list_prod = $list_prod . " <span class='label label-info'>Ожидание печати</span>";
                            break;
                        } else {
                            $list_prod = $list_prod . "";
                        }
                    }
                default:
                    $list_prod = $list_prod . "";
                    break;
            }

            $list_prod .= "</div>										 </div>";
        }
        echo $list_prod;
        break;


    /*---------------------------43---------------------------*/

    case '43':
        $id = $_GET['id'];

        $query = "select DATE_FORMAT(op.dates_rdy, '%Y-%m-%d %H:%i') dt, 
	op.ORDER_ID ,
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
	op.p_names,  op.size, op.fast,op.cl_file, op.print_diz, op.view_diz, op.view_press, op.status,op.p_names, op.total, op.size,op.cshivka,op.template, press_diz, id ,num_prod_ord,
	(select (select c.EMAIL from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) mail,
	(select (select c.PHONE_CITY from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel,
	(select (select c.PHONE_MOB from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel2,
	(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
	(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm 	,
	(select u.USER_FIO from users u where u.USER_LOGIN = (select lt.users from lock_task lt where lt.id_prod = op.id AND lt.flags = 1 AND lt.oper = op.status  LIMIT 1) LIMIT 1)  name_user,
	op.comment	,
	(select (select c.temp from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) temps		,
	op.units			,
	(select  DATE_FORMAT(lk.datetime, '%Y-%m-%d %H:%i') from log_task lk where lk.id_prod = op.id and lk.status_old = '' ORDER BY lk.id LIMIT 1) date_ot								
	from order_product op where id =" . $id;
        $result = mysql_query($query) or die($query);
        $list_prod1 = '';
        $list_prod = '';
        while ($row = mysql_fetch_row($result)) {


            $list_prod .= "<div class = 'row'><div class='col-md-2'>Наименование: </div><div class='col-md-9'>" . $row[12] . "</div> </div> ";
            if ($row[28] == 'тыс.шт.') {
                $list_prod .= "<div class = 'row'><div class='col-md-2'>Тираж: </div><div class='col-md-9'>" . ((double)$row[13] * 1000) . "</div> </div> ";
            } else {
                $list_prod .= "<div class = 'row'><div class='col-md-2'>Тираж: </div><div class='col-md-9'>" . $row[13] . "</div> </div> ";
            }

            $list_prod .= "<div class = 'row'><div class='col-md-2'>Размер: </div><div class='col-md-9'>" . $row[14] . " </div> </div> ";
            $cshivka = explode("|", $row[15]);
            if ($cshivka[0] != "" and $cshivka[0] != "0") {
                $list_prod .= "<div class = 'row'><div class='col-md-5'>Переплет по " . $cshivka[1] . " староне; ";

                switch ($cshivka[0]) {
                    case '1':
                        $list_prod .= 'пружина 6,4 мм';
                        break;
                    case '2':
                        $list_prod .= 'пружина 8,0 мм';
                        break;
                    case '3':
                        $list_prod .= 'пружина 9,5 мм';
                        break;
                    case '4':
                        $list_prod .= 'пружина 11,0 мм';
                        break;
                    case '5':
                        $list_prod .= 'пружина 12,7 мм';
                        break;
                    case '6':
                        $list_prod .= 'пружина 14,3 мм';
                        break;
                    case '7':
                        $list_prod .= 'скоба';
                        break;
                    case '8':
                        $list_prod .= 'Твердая обложка (PUR)';
                        break;
                    case '9':
                        $list_prod .= 'Твердая обложка (скобы)';
                        break;
                    case '10':
                        $list_prod .= 'Твердая обложка';
                        break;
                    case '11':
                        $list_prod .= 'Твердая обложка (пружина)';
                        break;
                    case '12':
                        $list_prod .= 'термоклей';
                        break;
                    case '13':
                        $list_prod .= 'нитка';
                        break;
                }
                $list_prod .= "</div> </div>";
            }

            $temp = explode("^", $row[16]);
            $eqq = "";
            for ($i = 0; $i < count($temp); $i++) {
                $z = $i + 1;
                $part = explode("|", $temp[$i]);

                $mn = 1;
                if ($row[28] == 'тыс.шт.') {
                    $mn = 1000;
                }
                $list_prod .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
	<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
                if ($part[0] != "" and $part[0] != '0') {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>" . $part[0] . '</div> </div>';
                } else {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_" . $z . '</div> </div>';
                }
                if ($part[1] != "" and $part[1] != '0') {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>" . $part[1] . '</div> </div>';
                }
                if ($part[2] != "" and $part[2] != '0') {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>" . $part[2] . '</div> </div>';
                }
                if ($part[3] != "" and $part[3] != '0' and $part[3] != 'Выберите') {
                    $eqq .= $part[3] . "<br>";
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>" . $part[3] . "</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>" . $part[4] . '</div> </div>';
                }
                if ($part[6] != "" and $part[6] != '0') {
                    $mat = explode(":", $part[6]);
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>" . $mat[0] . ' ' . $part[5] . "</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> " . $mat[1] . '</div> </div>';
                }
                if ($part[7] != "" and $part[7] != '0') {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";
                }
                if ($part[8] != "" and $part[8] != '0') {
                    $list_prod .= "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>" . $part[8] . '</div> </div>';
                }
                if ($part[9] != "" and $part[9] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>" . $part[9] . "</div><div class='col-md-1'>" . $part[9] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[10] != "" and $part[10] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>" . $part[10] . "</div><div class='col-md-1'>" . $part[10] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[11] != "" and $part[11] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>" . $part[11] . "</div><div class='col-md-1'>" . $part[11] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[12] != "" and $part[12] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Отверстия: " . $part[13] . " </div><div class='col-md-1'>" . $part[12] . "</div><div class='col-md-1'>" . $part[12] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[14] != "" and $part[14] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Люверс: " . $part[15] . "</div><div class='col-md-1'>" . $part[14] . "</div><div class='col-md-1'>" . $part[14] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[16] != "" and $part[16] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>" . $part[16] . "</div><div class='col-md-1'>" . $part[16] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[17] != "" and $part[17] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>" . $part[17] . "</div><div class='col-md-1'>" . $part[17] * $row[13] * $mn . '</div> </div>';
                }
                if ($part[18] != "" and $part[18] != '0') {
                    $list_prod1 .= "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>" . $part[18] . "</div><div class='col-md-1'>" . $part[18] * $row[13] * $mn . '</div> </div>';
                }
                if ($list_prod1 != "") {
                    $list_prod .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-1'>На 1 изд.</div><div class='col-md-1'>На тираж</div> </div>";
                    $list_prod .= $list_prod1;

                }
            }
        }
        echo "<div class = 'row'><div class='col-md-1'></div><div class='col-md-11'>" . $list_prod . "</div></div>";

        break;


    /*---------------------------44---------------------------*/

    case '44':

        if ($login == 'admins' || ($admin == 4 && isset($_GET['allListOrdersWork']))) {
            $query = "select t7.dt,t7.ORDER_ID,t7.client_name name,t7.status,t7.flag,t7.num_prod_ord,t7.p_names,t7.prob,t7.comm,t7.id,t7.total,t7.rdy,IF(t8.TOTAL_PROD is null, 0, t8.TOTAL_PROD) zakaz
					from
						(select t5.id,t5.dt,t5.ORDER_ID,t5.p_names,t5.status,t5.num_prod_ord,t5.total,t5.client_name,t5.prob,t5.comm,IF(t6.FINAL_TOTAL is null, 0, t6.FINAL_TOTAL) rdy,t5.flag
						from
							(select t3.id,t3.dt,t3.ORDER_ID,t3.p_names,t3.status,t3.num_prod_ord,(IF(t3.units <> 'тыс.шт.', t3.total, t3.total * 1000 )) total,t4.client_name,
								(select tt2.prob from log_task tt2 where tt2.id_prod = t3.ID and tt2.status_new = t3.status ORDER BY tt2.id DESC LIMIT 1) prob,
								(IF((select flags from lock_task lt where lt.id_prod = t3.id and lt.oper = t3.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = t3.id and lt.oper = t3.status ORDER BY lt.id DESC LIMIT 1))) flag,
								(select tt2.comm from log_task tt2 where tt2.id_prod = t3.ID and tt2.status_new = t3.status ORDER BY tt2.id DESC LIMIT 1) comm
							from
								(select t1.id,t1.dt,t1.ORDER_ID,t1.p_names,t1.status,t1.num_prod_ord,t1.units,t1.TOTAL,t2.client_id
								from
									(select op.ID,DATE_FORMAT(op.dates_rdy, '%d.%m.%Y ') dt,op.ORDER_ID,op.p_names,op.status,op.num_prod_ord,op.units,op.TOTAL from order_product op where op.`status`<>'' and op.`status`<>'3' and op.ORDER_ID>0) t1
								inner join
									(select number,client_id from orders) t2
								on t1.ORDER_ID=t2.number) t3
							left join
								(select id,client_name from clients) t4
							on t3.client_id=t4.id) t5
						left join
							(select pj.ID_PROD,sum(pj.FINAL_TOTAL) FINAL_TOTAL from plan_job pj where pj.STATUS = 2 GROUP BY pj.ID_PROD) t6
						on t5.id=t6.ID_PROD) t7
					left join
						(select pj.ID_PROD,sum(pj.TOTAL_PROD) TOTAL_PROD from plan_job pj where pj.STATUS = 1 GROUP BY pj.ID_PROD) t8
					on t7.id=t8.ID_PROD";
        } else {
            //проверяем или есть совмещение
            $combo = "";
            $slct = "SELECT login_children,active FROM combo_users WHERE login_parent='$login' ORDER BY id DESC LIMIT 1";
            $q = mysql_query($slct) or die(null);
            if ($r = mysql_fetch_array($q)) {
                if ($r['active'] == 0) {
                    $combo = " or user_id='" . $r['login_children'] . "'";
                }
            }

            $query = "select t7.dt,t7.ORDER_ID,t7.client_name name,t7.status,t7.flag,t7.num_prod_ord,t7.p_names,t7.prob,t7.comm,t7.id,t7.total,t7.rdy,IF(t8.TOTAL_PROD is null, 0, t8.TOTAL_PROD) zakaz
				from
					(select t5.id,t5.dt,t5.ORDER_ID,t5.p_names,t5.status,t5.num_prod_ord,t5.total,t5.client_name,t5.prob,t5.comm,IF(t6.FINAL_TOTAL is null, 0, t6.FINAL_TOTAL) rdy,t5.flag
					from
						(select t3.id,t3.dt,t3.ORDER_ID,t3.p_names,t3.status,t3.num_prod_ord,(IF(t3.units <> 'тыс.шт.', t3.total, t3.total * 1000 )) total,t4.client_name,
							(select tt2.prob from log_task tt2 where tt2.id_prod = t3.ID and tt2.status_new = t3.status ORDER BY tt2.id DESC LIMIT 1) prob,
							(IF((select flags from lock_task lt where lt.id_prod = t3.id and lt.oper = t3.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = t3.id and lt.oper = t3.status ORDER BY lt.id DESC LIMIT 1))) flag,
							(select tt2.comm from log_task tt2 where tt2.id_prod = t3.ID and tt2.status_new = t3.status ORDER BY tt2.id DESC LIMIT 1) comm
						from
							(select t1.id,t1.dt,t1.ORDER_ID,t1.p_names,t1.status,t1.num_prod_ord,t1.units,t1.TOTAL,t2.client_id
							from
								(select op.ID,DATE_FORMAT(op.dates_rdy, '%d.%m.%Y ') dt,op.ORDER_ID,op.p_names,op.status,op.num_prod_ord,op.units,op.TOTAL from order_product op where op.`status`<>'' and op.`status`<>'3' and op.ORDER_ID>0) t1
							inner join
								(select number,client_id from orders where user_id='" . $login . "'" . $combo . ") t2
							on t1.ORDER_ID=t2.number) t3
						left join
							(select id,client_name from clients) t4
						on t3.client_id=t4.id) t5
					left join
						(select pj.ID_PROD,sum(pj.FINAL_TOTAL) FINAL_TOTAL from plan_job pj where pj.STATUS = 2 GROUP BY pj.ID_PROD) t6
					on t5.id=t6.ID_PROD) t7
				left join
					(select pj.ID_PROD,sum(pj.TOTAL_PROD) TOTAL_PROD from plan_job pj where pj.STATUS = 1 GROUP BY pj.ID_PROD) t8
				on t7.id=t8.ID_PROD";
        }
        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            if ($login != 'admins' && $login != 'admin' && $row[3] == 4)
                continue;
            $list_prod1 = "<input type='checkbox' name='chjob' value='" . $row[9] . "'>";
            $list_prod = "";
            switch ($row[3]) {
                case 4:
                    $list_prod = "<span class='label label-danger '>Брак/Отказ</span>";
                    break;
                case 20:
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case 0:
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case 1:
                    $list_prod = "<span class='label label-info '>в цеху</span>";
                    break;
                case 2:
                    $list_prod = " <span class='label label-success'>Готово</span>";
                    break;
                case 21:
                    $list_prod = " <span class='label label-danger'>Cогласование с клиентом</span>";
                    break;
                case 10:
                    if ($row[4] == '1') {
                        $list_prod = " <span class='label label-primary'>Дизайн " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание дизайна</span>";
                        break;
                    }
                    break;
                case 11:
                    if ($row[4] == '1') {
                        $list_prod = "<span class='label label-primary '>Препресс " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание препресса</span>";
                        break;
                    }
                case 12:
                    if ($row[4] == '1') {
                        $list_prod = " <span class='label label-info'>Печатает " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = " <span class='label label-info'>Ожидание печати</span>";
                        break;
                    }
                default:
                    $list_prod = $list_prod . "";
                    break;
            }
            $json[] = array(
                'flags' => $list_prod1,
                'dats' => $row[0],
                'ids' => "<a href = '/pages/pg/_addAcct.php?id=" . $row[1] . "'>" . $row[1] . '_' . $row[5] . '</a>',
                'namess' => $row[2],
                'namess_orod' => $row[6],
                'stast' => $list_prod,
                'prob' => $row[7],
                'comm' => $row[8],
                'total' => $row[10],
                'rdy' => $row[11],
                'zakaz' => $row[12],
                'info' => "<a onClick='info_acct($row[9])'><button type= 'button' class='btn btn-info btn-circle'><span class='glyphicon glyphicon-info-sign  '></span></button></a>",
            );
        }

        //.'_'.$row[5]
        echo json_encode($json);


        break;

    /*---------------------------45---------------------------*/

    case '45':
        $query = "select 
	DATE_FORMAT(op.dates_rdy, '%d.%m.%Y  %H:%i') dt,
	op.ORDER_ID , 
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	op.status,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, 1)) flag,
	op.num_prod_ord,
	op.p_names,					op.id		,
	(IF(op.units <> 'тыс.шт.', op.total, op.total * 1000 )) total,
	(IF((select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD))) rdy ,
	(IF((select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD)))zakaz 
	, op.p_names, op.total, op.size,op.cshivka, op.template 
	
	
	from (SELECT number FROM orders  ) o , order_product op where op.ORDER_ID = o.number and op.status <> '' AND op.status <> '2' ORDER BY op.dates_rdy";
        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $list_prod = '';
            $list_prod1 = '';
            $list_prod3 = '';
            $list_prod2 = '';
            switch ($row[3]) {
                case 4:
                    $list_prod2 = $list_prod2 . "Брак/Отказ";
                    break;
                case 0:
                    $list_prod2 = "Возврат";
                    break;
                case 21:
                    $list_prod2 = "Согласование";
                    break;
                case 20:
                    $list_prod2 = "Возврат";
                    break;
                case 1:
                    $list_prod2 = "в цеху";
                    break;
                case 2:
                    $list_prod2 = " Готово";
                    break;
                case 10:
                    $list_prod2 = " Дизайн";
                    break;
                case 11:
                    if ($row[4] == '1') {
                        $list_prod2 = "Препресс";
                        break;
                    } else {
                        $list_prod2 = "Ожидание препресса";
                        break;
                    }
                case 12:
                    if ($row[4] == '1') {
                        $list_prod2 = " Печатается";
                        break;
                    } else {
                        $list_prod2 = "Ожидание печати";
                        break;
                    }
                default:
                    $list_prod2 = "";
                    break;
            }


            $list_prod3 .= $row[13] . " ; ";
            $cshivka = explode("|", $row[14]);
            if ($cshivka[0] != "" and $cshivka[0] != "0") {
                $list_prod3 .= $cshivka[1] . ";";

                switch ($cshivka[0]) {
                    case '1':
                        $list_prod3 .= 'пружина 6,4 мм;';
                        break;
                    case '2':
                        $list_prod3 .= 'пружина 8,0 мм;';
                        break;
                    case '3':
                        $list_prod3 .= 'пружина 9,5 мм;';
                        break;
                    case '4':
                        $list_prod3 .= 'пружина 11,0 мм;';
                        break;
                    case '5':
                        $list_prod3 .= 'пружина 12,7 мм;';
                        break;
                    case '6':
                        $list_prod3 .= 'пружина 14,3 мм;';
                        break;
                    case '7':
                        $list_prod3 .= 'скоба;';
                        break;
                    case '8':
                        $list_prod3 .= 'Твердая обложка (PUR);';
                        break;
                    case '9':
                        $list_prod3 .= 'Твердая обложка (скобы);';
                        break;
                    case '10':
                        $list_prod3 .= 'Твердая обложка;';
                        break;
                    case '11':
                        $list_prod3 .= 'Твердая обложка (пружина);';
                        break;
                    case '12':
                        $list_prod3 .= 'термоклей;';
                        break;
                    case '13':
                        $list_prod3 .= 'нитка;';
                        break;
                    case '14':
                        $cshivk .= 'пружины 6,4 мм';
                        break;
                    case '15':
                        $cshivk .= 'пружины 6,4 мм';
                        break;
                    case '16':
                        $cshivk .= 'пружина 8,0 мм';
                        break;
                    case '17':
                        $cshivk .= 'пружина 8,0 мм';
                        break;
                }

            } else $list_prod3 .= ";;";


            $temp = explode("^", $row[15]);
            for ($i = 0; $i < count($temp); $i++) {
                $list_prod = '';
                $list_prod1 = '';
                $z = $i + 1;
                $part = explode("|", $temp[$i]);

                if ($part[0] != "" and $part[0] != '0') {
                    $list_prod .= " " . $part[0] . ";";
                } else {
                    $list_prod .= "; ";
                }
                if ($part[1] != "" and $part[1] != '0') {
                    $list_prod .= " " . $part[1] . ";";
                } else {
                    $list_prod .= "; ";
                }
                if ($part[2] != "" and $part[2] != '0') {
                    $list_prod .= " " . $part[2] . ";";
                } else {
                    $list_prod .= "; ";
                }
                if ($part[3] != "" and $part[3] != '0' and $part[3] != 'Выберите') {
                    $list_prod .= "" . $part[3] . ';  ' . $part[4] . ";";
                } else {
                    $list_prod .= "; ;";
                }
                if ($part[6] != "" and $part[6] != '0') {
                    $mat = explode(":", $part[6]);
                    $list_prod .= "" . $mat[0] . ' ' . $part[8] . ' ;' . $mat[1] . ";";
                } else {
                    $list_prod .= ";; ";
                }
                if ($part[7] != "" and $part[7] != '0') {
                    $list_prod .= "YES ;";
                } else {
                    $list_prod .= "NO; ";
                }
                if ($part[8] != "" and $part[8] != '0') {
                    $list_prod .= $part[8] . ";";
                } else {
                    $list_prod .= "; ";
                }
                if ($part[9] != "" and $part[9] != '0') {
                    $list_prod1 .= $part[9] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($part[10] != "" and $part[10] != '0') {
                    $list_prod1 .= $part[10] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($part[11] != "" and $part[11] != '0') {
                    $list_prod1 .= $part[11] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($part[12] != "" and $part[12] != '0') {
                    $list_prod1 .= $part[13] . "  ; " . $part[12] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ;";
                }
                if ($part[14] != "" and $part[14] != '0') {
                    $list_prod1 .= $part[15] . " ;" . $part[14] * $row[8] . ";";
                } else {
                    $list_prod1 .= ";; ";
                }
                if ($part[16] != "" and $part[16] != '0') {
                    $list_prod1 .= $part[16] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($part[17] != "" and $part[17] != '0') {
                    $list_prod1 .= $part[17] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($part[18] != "" and $part[18] != '0') {
                    $list_prod1 .= $part[18] * $row[8] . ";";
                } else {
                    $list_prod1 .= "; ";
                }
                if ($list_prod1 != "") {

                    $list_prod .= $list_prod1;
                }


                $json[] = array(
                    'dats' => $row[0],
                    'ids' => $row[1] . '_' . $row[5],
                    'namess' => $row[2],
                    'namess_orod' => $row[6],
                    'stast' => $list_prod2,
                    'total' => $row[8],
                    'rdy' => number_format($row[9], 1, ',', ''),
                    'zakaz' => number_format($row[10], 2, ',', ''),
                    'temp' => $list_prod3 . $list_prod,
                );

            }


        }

        //.'_'.$row[5]
        echo json_encode($json);

        break;
    //----------------------------------------------46-----------------------------
    case '46':

        $dt1 = $_GET['dt1'];
        $dt2 = $_GET['dt2'];

        $dt1_ = $_GET['dt1_'];
        $dt2_ = $_GET['dt2_'];
        $user_id = $_GET['user_id'];


        $query = "	UPDATE users SET DT1= '{$dt1_}' , DT2= '{$dt2_}', cl_id = '-1' WHERE USER_LOGIN ='{$login}';";
        mysql_query($query) or die($query);

        if ($user_id == '0') {
            $query = "SELECT o.NUMBER,  DATE_FORMAT(o.DATE_OR, '%d.%m.%Y'),(select user_fio from users where user_login = o.user_id) user_name, 
	o.STATUS_ID,(select client_name from clients where id = o.client_id)  client_name,
	(select email from clients where id = o.client_id)  client_mail, o.client_id,
	(select UNP from clients where id = o.client_id)  client_unp	, 
	(select ROUND(sum(opl.ALL_SUM),2) from oplati opl where  opl.ORDER_NUM = o.NUMBER  and opl.id > 0 group by opl.ORDER_NUM) opl,
	(select ROUND(sum(tn.summ),2) from tn_list_par tn where  tn.order_id = o.NUMBER and tn.del = 0 and num_tm <> 0 group by tn.order_id) ttn,
	(select ROUND(SUM( ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) sum from order_product op where op.order_id = o.NUMBER ORDER BY op.order_id) sumss,
	DATE_FORMAT(o.DATE_OR, '%Y-%m-%d')						
	
	FROM (SELECT * FROM orders WHERE dATE_OR >= '" . $dt1 . "' AND dATE_OR <= '" . $dt2 . "' + INTERVAL 1 DAY) o ";
        } else {
            $query = "SELECT o.NUMBER,  DATE_FORMAT(o.DATE_OR, '%d.%m.%Y'), (select user_fio from users where user_login = o.user_id) user_name, 
	o.STATUS_ID, (select client_name from clients where id = o.client_id)  client_name,
	(select email from clients where id = o.client_id)  client_mail, o.client_id,
	
	(select UNP from clients where id = o.client_id)  client_unp		,
	( select ROUND(sum(opl.ALL_SUM),2) from oplati opl where  opl.ORDER_NUM = o.NUMBER  and opl.id > 0 group by opl.ORDER_NUM) opl,
	(select ROUND(sum(tn.summ),2) from tn_list_par tn where  tn.order_id = o.NUMBER and tn.del = 0 and num_tm <> 0 group by tn.order_id) ttn	,
	(		  select ROUND(SUM( ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) sum from order_product op where op.order_id = o.NUMBER ORDER BY op.order_id) sumss,
	DATE_FORMAT(o.DATE_OR, '%Y-%m-%d')				
	FROM (SELECT * FROM orders WHERE USER_ID = '" . $user_id . "' AND dATE_OR >= '" . $dt1 . "' AND dATE_OR <= '" . $dt2 . "' + INTERVAL 1 DAY) o ";
        }

        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $aq = $row[8];
            $tn_sum = $row[9];
            $summ1 = $row[10];

            $opl = "";
            if ($admin == '4' or $admin == '2' or $login == '026' or $login == '030' or $login == '028' or $login == '033' or $login == '032') {
                $opl = "<a onclick = add_oplat('$row[0]','$row[6]')><span class = 'pull-right'><i class='glyphicon glyphicon-euro'></i></<span></a>";
            }
            $json[] = array(
                'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                'dates' => $row[1],
                'id' => $row[0],
                'names' => $row[4],
                'unp' => $row[7],
                'mng' => $row[2],
                'sum1' => $summ1,
                'sum2' => number_format($tn_sum, 2, '.', ' '),
                'TM' => $list_prod,
                'opl' => number_format($aq, 2, '.', ' '),
                'opl_add' => $opl,
                'info' => "<a onClick='_smena_cl($row[0])'><button type= 'button' class='btn btn-info btn-circle'><span class='glyphicon glyphicon-user    '></span></button></a>" . '&nbsp;&nbsp;<button type= "button" class="btn btn-info btn-circle" onClick="_smena_dt(' . "'$row[0]','$row[11]'" . ' )"><span class="glyphicon glyphicon-calendar"></span></button>',
                'edit' => "<a onClick='acct($row[0])'><i class='fa  fa-edit' aria-hidden='true'></i></a> ",

            );
        }

        if (count($json) == 0) {

            if ($user_id == '0') {
                $query = "SELECT o.NUMBER,  DATE_FORMAT(o.DATE_OR, '%d.%m.%Y'),(select user_fio from users where user_login = o.user_id) user_name, 
	o.STATUS_ID,(select client_name from clients where id = o.client_id)  client_name,
	(select email from clients where id = o.client_id)  client_mail, o.client_id,
	(select UNP from clients where id = o.client_id)  client_unp	, 
	(select ROUND(sum(opl.ALL_SUM),2) from oplati opl where  opl.ORDER_NUM = o.NUMBER  and opl.id > 0 group by opl.ORDER_NUM) opl,
	(select ROUND(sum(tn.summ),2) from tn_list_par tn where  tn.order_id = o.NUMBER and tn.del = 0 and num_tm <> 0 group by tn.order_id) ttn,
	(select ROUND(SUM( ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) sum from order_product op where op.order_id = o.NUMBER ORDER BY op.order_id) sumss	,
	DATE_FORMAT(o.DATE_OR, '%Y-%m-%d')									
	
	FROM (SELECT * FROM orders ORDER BY number DESC LIMIT 20) o ";
            } else {
                $query = "SELECT o.NUMBER,  DATE_FORMAT(o.DATE_OR, '%d.%m.%Y'), (select user_fio from users where user_login = o.user_id) user_name, 
	o.STATUS_ID, (select client_name from clients where id = o.client_id)  client_name,
	(select email from clients where id = o.client_id)  client_mail, o.client_id,
	
	(select UNP from clients where id = o.client_id)  client_unp		,
	( select ROUND(sum(opl.ALL_SUM),2) from oplati opl where  opl.ORDER_NUM = o.NUMBER  and opl.id > 0 group by opl.ORDER_NUM) opl,
	(select ROUND(sum(tn.summ),2) from tn_list_par tn where  tn.order_id = o.NUMBER and tn.del = 0 and num_tm <> 0 group by tn.order_id) ttn	,
	(		  select ROUND(SUM( ROUND((op.price / 1.2) ,2) * op.total * 1.2),2) sum from order_product op where op.order_id = o.NUMBER ORDER BY op.order_id) sumss,
	DATE_FORMAT(o.DATE_OR, '%Y-%m-%d')								
	FROM (SELECT * FROM orders WHERE USER_ID = '" . $user_id . "' ORDER BY number DESC LIMIT 20) o ";
            }

            $json = array();
            $result = mysql_query($query) or die($query);
            while ($row = mysql_fetch_row($result)) {
                $aq = $row[8];
                $tn_sum = $row[9];
                $summ1 = $row[10];

                $opl = "";
                if ($admin == '4' or $admin == '2' or $login == '026' or $login == '030' or $login == '028' or $login == '033' or $login == '032') {
                    $opl = "<a onclick = add_oplat('$row[0]','$row[6]')><span class = 'pull-right'><i class='glyphicon glyphicon-euro'></i></<span></a>";
                }
                $json[] = array(
                    'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
                    'dates' => $row[1],
                    'id' => $row[0],
                    'names' => $row[4],
                    'unp' => $row[7],
                    'mng' => $row[2],
                    'sum1' => $summ1,
                    'sum2' => number_format($tn_sum, 2, '.', ' '),
                    'TM' => $list_prod,
                    'opl' => number_format($aq, 2, '.', ' '),
                    'opl_add' => $opl,
                    'info' => "<a onClick='_smena_cl($row[0])'><button type= 'button' class='btn btn-info btn-circle'><span class='glyphicon glyphicon-user    '></span></button></a>" . '&nbsp;&nbsp;<button type= "button" class="btn btn-info btn-circle" onClick="_smena_dt(' . "'$row[0]','$row[11]'" . ' )"><span class="glyphicon glyphicon-calendar"></span></button>',
                    'edit' => "<a onClick='acct($row[0])'><i class='fa  fa-edit' aria-hidden='true'></i></a>",
                );
            }


        }


        echo json_encode($json);

        break;

    //----------------------------------------------47-----------------------------
    case '47':

        $id_ord = $_GET['id_ord'];
        $post_fio = $_GET['post_fio'];
        $region_id = $_GET['region_id'];
        $post_city = $_GET['post_city'];
        $post_street = $_GET['post_street'];
        $post_house_num = $_GET['post_house_num'];
        $post_raion = $_GET['post_raion'];
        $post_room = $_GET['post_room'];
        $post_index = $_GET['post_index'];
        $post_phone = $_GET['post_phone'];
        $post_price = $_GET['post_price'];
        $post_house_kor = $_GET['post_house_kor'];
        $view_post = $_GET['view_post'];
        $post_house_kor = $_GET['post_house_kor'];
        $post_date = $_GET['post_date'];
        $post_track = $_GET['post_track'];
        $post_mail = $_GET['post_mail'];
        $str = $_GET['str'];
        $goods = $_GET['goods'];
        $query = "select CLIENT_ID from orders where number =" . $id_ord;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $id_cl = $row[0];
        }

        $query = "INSERT INTO mail (id_ord, fio, obl, city, house_num, room, phone, index_, price, street,id_cl,view_,user_login,korp,date_otpr,track_cod,parm,raion, goods, mail) 
	VALUES ('{$id_ord}','{$post_fio}','{$region_id}','{$post_city}','{$post_house_num}','{$post_room}','{$post_phone}','{$post_index}','{$post_price}','{$post_street}','{$id_cl}','{$view_post}','{$login}','{$post_house_kor}','{$post_date}','{$post_track}','{$str}','{$post_raion}','{$goods}','{$post_mail}');";
        mysql_query($query) or die($query);
        $insert_ID = mysql_insert_id();

        $parent_company = (isset($_GET['parent_company']) && !empty($_GET['parent_company'])) ? $_GET['parent_company'] : 1;
        $sett_track = ($parent_company == 2) ? '20' : '16';

        $query = "select val from settings where id = {$sett_track}; ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $last_num = $row[0];
        }
        $last_num = (int)$last_num + 1;
        $kol = strlen($last_num);
        while ($kol < 8) {
            $last_num = "0" . $last_num;
            $kol = strlen($last_num);
        }


        $query = "	UPDATE settings SET 
	val = '" . $last_num . "'
	WHERE ID = {$sett_track};";
        mysql_query($query) or die($query);

        echo $insert_ID;

        break;


    /*---------------------------48---------------------------*/
    case '48':
        $id_cl = $_GET['id_cl'];

        $query = "select view_,id_ord, (select c.CLIENT_NAME from clients c where c.ID = m.id_cl) names ,track_cod,date,status, id,price,mail, 
				(select c.EMAIL from clients c where c.ID = m.id_cl) mails  from mail m where m.id_cl = '" . $id_cl . "' ";
        if ($id_cl == '-1') {
            //$query="select view_,id_ord, (select c.CLIENT_NAME from clients c where c.ID = m.id_cl) names ,track_cod   from mail m where m.user_login = '".$login."' ";
            $query = "select view_,id_ord, (select c.CLIENT_NAME from clients c where c.ID = m.id_cl) names ,track_cod,date,status, id,price,mail, 
					(select c.EMAIL from clients c where c.ID = m.id_cl) mails     from mail m ";
        }


        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $list_prod = "";
            $vw = $row[0];
            if ($row[0] == NULL) {
                $vw = '';
            }
            $dates = '';
            $status = '';
            /*if($row[3] != '' AND $row[4] == '' AND $row[5] == '') {
				$status1 = explode("|", track_post($row[3]));
				$dates = $status1[0];
				$status = $status1[1];
				$str = strtoupper($status1[1]);

				$pos = strpos(strtoupper($status1[1]), 'Вручено ');
				IF($pos != FALSE){
					$qwe = "UPDATE mail SET date='{$dates}', status='{$status}' WHERE ID=". $row[6];
					mysql_query($qwe) or die($qwe);
				}
			} */
            if ($row[4] != '') {
                $dates = $row[4];
            }
            if ($row[5] != '') {
                $status = $row[5];
            }
            $mail3 = '';
            if ($row[9] != '') {
                $mail3 = $row[9];
            } else {
                $mail3 = $row[8];
            }

            $json[] = array(
                'view' => $vw,
                'num' => $row[1],
                'cl' => $row[2],
                'price' => $row[7],
                'track' => $row[3],
                'dates' => $dates,
                'status' => $status,
                'rty' => ' <button type= "button" class="btn btn-info" onClick="repMail(' . "'" . $row[3] . "'" . ", " . "'" . $mail3 . "'" . ')">Повторить на eMail</button> ',
            );
        }

        //.'_'.$row[5]

        echo json_encode($json);
        break;

    /*---------------------------49---------------------------*/
    case '49':
        $id = $_GET['id'];
        $qr2 = "SELECT (select f.FIRM_NAME from firms f where f.ID = t.cod_firm) name, t.num , DATE_FORMAT(t.dt, '%d.%m.%Y') , tm.total, tm.unit , ROUND(tm.sum_all/tm.total,2) price, 
	ROUND((ROUND(tm.sum_all/tm.total,2) / replace((select st.val from settings_attr st where st.SET_ID = 2 and st.DATE_VAL <=  t.dt ORDER BY st.ID DESC LIMIT 1), ',', '.') ),2) USD, 
	ROUND((select m.M_PRICE from material_attr m where m.id = tm.id_mat),2) price_c,  replace((select st.val from settings_attr st where st.SET_ID = 2 and st.DATE_VAL <=  t.dt ORDER BY st.ID DESC LIMIT 1), ',', '.')  kurs
	from TTN_mater tm, TTN t where tm.id_mat = " . $id . " and t.ID = tm.id_TTN";
        $list_prod = "";
        $iy = 0;
        $rs = mysql_query($qr2) or die($qr2);
        $list_prod =
            "<div class = 'row'>
	
	<div class='col-md-1'></div> 
	<div class='col-md-2'>Наименование</div>
	<div class='col-md-1'>Номер ТТН</div> 
	<div class='col-md-1'>Дата ТТН</div> 
	<div class='col-md-1'>Кол-во</div> 
	<div class='col-md-1'>Ед. изм.</div> 
	<div class='col-md-1'>Цена ТТН BYN</div>
	<div class='col-md-1'>Цена ТТН,$</div>
	<div class='col-md-1'>Цена карточки,$ </div>
	<div class='col-md-1'>Курс</div>
	</div>";
        while ($rw = mysql_fetch_row($rs)) {
            $iy++;
            $list_prod = $list_prod .
                "<div class = 'row'>
	<div class='col-md-1'>" . $iy . ".</div> 
	<div class='col-md-2'>" . $rw[0] . "</div> 
	<div class='col-md-1'>" . $rw[1] . "</div> 
	<div class='col-md-1'>" . $rw[2] . "</div>
	<div class='col-md-1'>" . $rw[3] . "</div> 
	<div class='col-md-1'>" . $rw[4] . "</div>
	<div class='col-md-1'>" . $rw[5] . "</div>
	<div class='col-md-1'>" . $rw[6] . "</div>
	<div class='col-md-1'>" . $rw[7] . "</div>
	<div class='col-md-1'>" . $rw[8] . "</div>
	</div>";
        }
        echo $list_prod;


        break;

    //GRUD
    //ACCT
    case '200':
        $par = $_GET['orderClient'];
        $parent_company = empty($_GET['parent_company']) ? 1 : $_GET['parent_company'];
        $orderClient = (int)$par;
        $orderDate = date("H:i:s");
        if (empty($login)) {
            die(null);
        }
        $orderUsers = $login;
        $val_ = $_GET['val_'];
        $date_ = $_GET['date_'];
        $orderStatus = 0;

        if ($date_ == "") {
            $date_ = date("Y-m-d");
        }

        $date_ = $date_ . " " . (string)$orderDate;
        //	echo $date_ .'<br>';
        $query = "select val from settings s where  s.id = 12";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $idd = $row[0];
        }
        if ($idd != '') {
            $query = "INSERT INTO orders (number, DATE_OR, USER_ID, STATUS_ID, CLIENT_ID,CUR_ID,parent_company) 
			VALUES('{$idd}'	,'{$date_}','{$orderUsers}',{$orderStatus},{$orderClient},'{$val_}',{$parent_company});";
            mysql_query($query) or die($query);
            $insert_ID = mysql_insert_id();
            $query = "	UPDATE settings SET 
			val = ''
			WHERE ID = 12;";
            mysql_query($query) or die($query);

        } else {
            $query = "INSERT INTO orders (DATE_OR, USER_ID, STATUS_ID, CLIENT_ID,CUR_ID,parent_company) 
			VALUES(	'{$date_}','{$orderUsers}',{$orderStatus},{$orderClient},'{$val_}',{$parent_company});";
            mysql_query($query) or die($query);
            $insert_ID = mysql_insert_id();
        }
        echo $insert_ID;

        break;

    case '201':

        $array_id = $_GET['array_id'];
        $parent_company = empty($_GET['parent_company']) ? 1 : $_GET['parent_company'];
        $orderUsers = $login;
        $query = "select DISTINCT client_id from orders o, (select ORDER_ID from order_product where FIND_IN_SET(id, '" . $array_id . "')) o2 where o.NUMBER = o2.order_id LIMIT 1";
        $result = mysql_query($query) or die($query);
        if ($row = mysql_fetch_row($result)) {
            $orderClient = $row[0];
        }
        $orderDate = date("Y-m-d H:i:s");
        $orderStatus = 0;
        $val_ = 933;
        $query = "INSERT INTO orders (DATE_OR, USER_ID, STATUS_ID, CLIENT_ID,CUR_ID,parent_company) 
		VALUES(	'{$orderDate}','{$orderUsers}',{$orderStatus},{$orderClient},'{$val_}',{$parent_company});";
        mysql_query($query) or die($query);
        $insert_ID_ord = mysql_insert_id();
        $kol = 0;
        $query = "select ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, dates_rdy, fast, code_stat from order_product where FIND_IN_SET(id, '" . $array_id . "')";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $kol++;

            $name_prod1 = str_replace(' ', '_', $row[10]);
            $name_prod1 = str_replace('\\', '_', $name_prod1);
            $name_prod1 = substr($name_prod1, 0, 25);
            $name_prod1 = str2url($name_prod1) . "_" . rand(1, 99);


            $name = $insert_ID_ord;

            $name_dir = "pg/files/prod/$name";
            if (is_dir($name_dir) == FALSE) {
                mkdir($name_dir, 0777);
            }
            $name = $name . '/' . $name_prod1;
            $name_dir = "pg/files/prod/$name/";
            if (is_dir($name_dir) == FALSE) {
                mkdir($name_dir, 0777);
            }
            $name_dir11 = "pg/files/prod/$name/client";
            $name_dir1 = "files/prod/$name/client";
            $name_r1 = "\\files\prod\\" . $name . "\\client";
            if (is_dir($name_dir11) == FALSE) {
                mkdir($name_dir11, 0777);
            }
            $name_dir21 = "pg/files/prod/$name/diz";
            $name_dir2 = "files/prod/$name/diz";
            $name_r2 = "\\files\prod\\" . $name . "\\diz";
            if (is_dir($name_dir21) == FALSE) {
                mkdir($name_dir21, 0777);
            }
            $name_dir31 = "pg/files/prod/$name/press";
            $name_dir3 = "files/prod/$name/press";
            $name_r3 = "\\files\prod\\" . $name . "\\press";
            if (is_dir($name_dir31) == FALSE) {
                mkdir($name_dir31, 0777);
            }
            //echo "pg/".$row[12]."<br> ".$name_dir21."<br> pg/"." ".$row[13]."<br> ".$name_dir31."<br>";

            copy_files("pg/" . $row[12], $name_dir31);
            copy_files("pg/" . $row[13], $name_dir21);
            $query1 = "INSERT INTO order_product (ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, num_prod_ord, code_stat) 
            VALUES(	'{$insert_ID_ord}','{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$row[5]}','{$row[6]}','{$row[7]}','{$row[8]}','{$row[9]}','{$row[10]}','{$row[11]}','{$name_dir3}','{$name_dir2}','','{$row[15]}','{$row[16]}','{$row[17]}','{$row[18]}','{$name_dir1}','{$row[20]}','{$kol}','{$row[23]}');";
            mysql_query($query1) or die($query1);
        }


        echo $insert_ID_ord;


        break;


    case '202':

        if ($admin == '4') {
            $query = "select 
	DATE_FORMAT(op.dates_rdy, '%Y-%m-%d  %H:%i') dt,
	op.ORDER_ID , 
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	op.status,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	op.num_prod_ord,
	op.p_names,				(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
	(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm,
	op.id		,
	(IF(op.units <> 'тыс.шт.', op.total, op.total * 1000 )) total,
	(IF((select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD))) rdy ,
	(IF((select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD)))zakaz 
	
	from (SELECT number FROM orders ) o , order_product op where op.ORDER_ID = o.number and (op.status <> '' AND op.status <> '3')
	";
        } else {
            $query = "select 
	DATE_FORMAT(op.dates_rdy, '%Y-%m-%d  %H:%i') dt,
	op.ORDER_ID , 
	(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
	op.status,
	(IF((select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) IS NULL, 0, (select flags from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1))) flag,
	op.num_prod_ord,
	op.p_names,				(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
	(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm,
	op.id,
	(IF(op.units <> 'тыс.шт.', op.total, op.total * 1000 )) total,
	
	(IF((select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 2 GROUP BY pj.ID_PROD))) rdy ,
	(IF((select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = op.id and pj.STATUS = 1 GROUP BY pj.ID_PROD)))zakaz ,
	( select (select u.USER_FIO from users u where u.user_login = lt.users) from lock_task lt where lt.id_prod = op.id and lt.oper = op.status ORDER BY lt.id DESC LIMIT 1) dd
	from (SELECT number FROM orders o where o.user_id = '" . $login . "') o , order_product op where op.ORDER_ID = o.number and (op.status <> '' AND  op.status <> '3')  ";
        }
        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $list_prod1 = "<input type='checkbox' name='chjob' value='" . $row[9] . "'>";
            $list_prod = "";
            switch ($row[3]) {
                case 4:
                    $list_prod = "<span class='label label-danger '>Брак/Отказ</span>";
                    break;
                case 0:
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case 20:
                    $list_prod = "<span class='label label-danger '>Возврат</span>";
                    break;
                case 1:
                    $list_prod = "<span class='label label-info '>в цеху</span>";
                    break;
                case 2:
                    $list_prod = " <span class='label label-success'>Готово</span>";
                    break;
                case 21:
                    $list_prod = " <span class='label label-danger'>Cогласование с клиентом</span>";
                    break;
                case 10:
                    if ($row[4] == '1') {
                        $list_prod = " <span class='label label-primary'>Дизайн " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание дизайна</span>";
                        break;
                    }
                    break;
                case 11:
                    if ($row[4] == '1') {
                        $list_prod = "<span class='label label-primary '>Препресс " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = "<span class='label label-primary '>Ожидание препресса</span>";
                        break;
                    }
                case 12:
                    if ($row[4] == '1') {
                        $list_prod = " <span class='label label-info'>Печатает " . $row[13] . "</span>";
                        break;
                    } else {
                        $list_prod = " <span class='label label-info'>Ожидание печати</span>";
                        break;
                    }
                default:
                    $list_prod = "";
                    break;
            }
            $json[] = array(
                'flags' => $list_prod1,
                'dats' => $row[0],
                'ids' => $row[1] . '_' . $row[5],
                'namess' => $row[2],
                'namess_orod' => $row[6],
                'stast' => $list_prod,
                'prob' => $row[7],
                'comm' => $row[8],
                'total' => $row[10],
                'rdy' => $row[11],
                'zakaz' => $row[12],
                'info' => "<a onClick='info_acct($row[9])'><button type= 'button' class='btn btn-info btn-circle'><span class='glyphicon glyphicon-info-sign  '></span></button></a>",
            );
        }


        echo json_encode($json);

        break;

    case '203':

        $id1 = $_GET['id1'];
        $query = "UPDATE order_product SET status='3' WHERE  FIND_IN_SET(ID , '" . $id1 . "') ";
        mysql_query($query) or die($query);
        break;

    case '204':

        $id1 = $_GET['id1'];

        $query = "select id,  p_names, units ,total,  (IF((select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = o.id and pj.STATUS = 2 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.FINAL_TOTAL) from plan_job pj where pj.ID_PROD = o.id and pj.STATUS = 2 GROUP BY pj.ID_PROD))) rdy ,
	(IF((select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = o.id and pj.STATUS = 1 GROUP BY pj.ID_PROD) IS NULL, 0, (select sum(pj.TOTAL_PROD) from plan_job pj where pj.ID_PROD = o.id and pj.STATUS = 1 GROUP BY pj.ID_PROD)))zakaz 
	from (select * from order_product op where FIND_IN_SET(id,'" . $id1 . "')) o";
        $result = mysql_query($query) or die($query);
        $list_prod = "";
        $list_prod = "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-3'><label>Наименование</label></div> 
	<div class='col-md-1'><label>Требуется</label></div> 
	<div class='col-md-1'><label>Готово</label></div>
	<div class='col-md-1'><label>Заказано</label></div>
	<div class='col-md-1'><label>Заказ</label></div>
	</div>
	<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-3'></div> 
	<div class='col-md-1'></div> 
	<div class='col-md-1'></div>
	<div class='col-md-1'></div>
	<div class='col-md-1'></div>
	</div>";
        while ($row = mysql_fetch_row($result)) {
            $total = (double)$row[3];
            if ($row[2] == 'тыс.шт.') {
                $total = $total * 1000;
            }
            $list_prod .=
                "<div class = 'row'>
	<div class='col-md-1'></div> 
	<div class='col-md-1'><input type='checkbox' name='chjob2' checked value='" . $row[0] . "'></div> 
	<div class='col-md-3'>$row[1]</div> 
	<div class='col-md-1'>$total</div> 
	<div class='col-md-1'>$row[4]</div>
	<div class='col-md-1'>$row[5]</div>
	<div class='col-md-1'><input name='total$row[0]' id = 'total$row[0]' type='text' value='$total' size='7'/></div>
	</div>";
        }
        mysql_close($connection);

        echo $list_prod;

        break;

    case '205':

        $dates = $_GET['dates'];

        $query = "
			select 
			pj.id, 
			pj.id_prod, 
			(select u.USER_FIO from users u where u.USER_LOGIN = pj.id_user_name) user_fio ,
			(select op.p_names from order_product op where op.ID = pj.id_prod) p_names, 
			(select op.units from order_product op where op.ID = pj.id_prod) units,
			(select op.total from order_product op where op.ID = pj.id_prod) total,
			(IF((select sum(pj2.FINAL_TOTAL) from plan_job pj2 where pj2.ID_PROD = pj.ID_PROD and pj2.STATUS = 1 GROUP BY pj2.ID_PROD) IS NULL, 0, 
			(select sum(pj2.FINAL_TOTAL) from plan_job pj2 where pj2.ID_PROD = pj.ID_PROD and pj2.STATUS = 2 GROUP BY pj2.ID_PROD))) rdy,
			pj.total_prod,
			(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = (select op.ORDER_ID from order_product op where op.ID = pj.id_prod)) name_cl,
			pj.status,
			(select op.num_prod_ord from order_product op where op.ID = pj.id_prod) num_prod_ord,
			(select op.ORDER_ID from order_product op where op.ID = pj.id_prod) id_ord,
			(select op.dates_rdy from order_product op where op.ID = pj.id_prod) dates_rdy
			from plan_job pj
			where pj.dates_ = '" . $dates . "' and 	pj.status = '0' group by pj.id_prod";
        $result = mysql_query($query) or die($query);
        $list_prod = "";
        $list_prod = "<div class = 'row'>
		
		<div class='col-md-1'></div> 
		<div class='col-md-2'><label>Менеджер</label></div> 
		<div class='col-md-2'><label>Контрагент</label></div> 
		<div class='col-md-2'><label>Наименование</label></div> 
		<div class='col-md-1'><label>Требуется</label></div> 
		<div class='col-md-1'><label>Изготовл.</label></div>
		<div class='col-md-1'><label>Заказано</label></div>
		<div class='col-md-1'><label>Заказ</label></div>
		</div>
		<div class = 'row'>
		
		<div class='col-md-1'></div> 
		<div class='col-md-2'></div> 
		<div class='col-md-2'></div> 
		<div class='col-md-2'></div> 
		<div class='col-md-1'></div> 
		<div class='col-md-1'></div>
		<div class='col-md-1'></div>
		<div class='col-md-1'></div>
		</div>";

        while ($row = mysql_fetch_array($result)) {
            $elem = array(
                'order' => $row[11] . "_" . $row[10],
                'client' => $row[8],
                'name_order' => $row[3],
                'dates_rdy' => date("G:i", strtotime($row['dates_rdy'])),
            );

            $array_data[] = $elem;

            $total = (double)$row[5];
            if ($row[4] == 'тыс.шт.') {
                $total = $total * 1000;
            }
            $list_prod .=
                "<div class = 'row'>
				
				<div class='col-md-1'><input type='hidden' id = 'id_zakaz$row[0]' value='$row[1]'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='chjob3' checked value='$row[0]'>&nbsp;&nbsp;&nbsp;&nbsp; $row[11]_$row[10]</div> 
				<div class='col-md-2'>$row[2]</div> 
				<div class='col-md-2'>$row[8]</div> 
				<div class='col-md-2'>$row[3]</div> 
				<div class='col-md-1'>$total</div> 
				<div class='col-md-1'>$row[6]</div>
				<div class='col-md-1'>$row[7]</div>
				<div class='col-md-1'><input name='total$row[0]' id = 'total$row[0]' type='text' value='" . $row[7] . "' size='7'/>&nbsp;&nbsp;&nbsp;&nbsp; <a href = 'pg/proc/plan_job2.php?id=" . $row[1] . "' target='_blank'><span class = 'pull-right'><i class='glyphicon glyphicon-tasks'></i></<span></a></div>
				</div>";
        }
        mysql_close($connection);

        $list_prod .=
            "
			<div class = 'row'>
			
			<div class='col-md-1'></div> 
			<div class='col-md-2'></div> 
			<div class='col-md-2'></div> 
			<div class='col-md-2'></div> 
			<div class='col-md-1'></div> 
			<div class='col-md-1'></div>
			<div class='col-md-1'></div>
			<div class='col-md-1'></div>
			</div>
			<div class = 'row'>
			
			<div class='col-md-1'></div> 
			<div class='col-md-2'></div> 
			<div class='col-md-2'></div> 	
			<div class='col-md-2'></div> 
			<div class='col-md-1'></div> 
			<div class='col-md-1'></div>
			<div class='col-md-1'></div>
			<div class='col-md-1'></div>
			</div>";

        //формируем отчет
        if (!empty($array_data) && is_array($array_data) && count($array_data)) {
            // Создадим папку если её нет
            if (!is_dir("tmp_files")) mkdir("tmp_files", 0777);
            // Создаем объект класса PHPExcel
            $xls = new PHPExcel();
            // Устанавливаем индекс активного листа
            $xls->setActiveSheetIndex(0);
            // Получаем активный лист
            $sheet = $xls->getActiveSheet();
            //Добавляем заголовки
            $sheet->setCellValue('A1', "Номер заказа");
            $sheet->setCellValue('B1', "Контрагент");
            $sheet->setCellValue('C1', "Наименование");
            $sheet->setCellValue('D1', "Время готовности");
            //пишем данные
            $num_rows = 1;
            foreach ($array_data as $key) {
                $num_rows++;
                //Добавляем данные
                $sheet->setCellValue('A' . $num_rows, $key['order']);
                $sheet->setCellValue('B' . $num_rows, $key['client']);
                $sheet->setCellValue('C' . $num_rows, $key['name_order']);
                $sheet->setCellValue('D' . $num_rows, $key['dates_rdy']);
            }

            // Выводим содержимое файла
            $objWriter = new PHPExcel_Writer_Excel5($xls);
            $objWriter->save('tmp_files/work_plan.xls');

            //скачиваем файл
            //file_force_download('tmp_files/work_plan.xls');
            /*echo "<script>
				var url = '../pg/modeler.php?dFile=' + JSON.stringify('tmp_files/work_plan.xls');
				location.href = url;
			</script>";*/
        }

        echo $list_prod;

        break;

    case '206':

        $id1 = $_GET['id1'];
        $dates = $_GET['dates'];


        $login = $_SESSION['login'];

        $strr = explode("|", $id1);
        for ($z = 0; $z < count($strr); $z++) {
            $str2 = explode("^", $strr[$z]);
            $id_prod = $str2[0];
            $total = str_replace(",", ".", $str2[1]);
            $query = "INSERT INTO PLAN_JOB (DATES_, ID_PROD, TOTAL_PROD, id_user_name) VALUES('{$dates}','{$id_prod}','{$total}','{$login}');";
            mysql_query($query) or die($query);

        }

        break;

    case '207':

        $id1 = $_GET['id1'];
        $dates = $_GET['dates'];


        $login = $_SESSION['login'];

        $strr = explode("|", $id1);
        for ($z = 0; $z < count($strr); $z++) {
            $str2 = explode("^", $strr[$z]);
            $id_prod = $str2[1];
            $total = str_replace(",", ".", $str2[2]);
            $query = "UPDATE PLAN_JOB SET status = 1, TOTAL_PROD = " . $total . "  WHERE id = " . $id_prod;
            mysql_query($query) or die($query);

        }

        break;

    case '208':

        $client = $_GET['client'];
        $num = $_GET['num'];
        $sum = $_GET['sum'];
        $sum = str_replace(",", ".", $sum);
        $sum = round($sum, 2);
        $date_ = $_GET['date'];
        $view_opl = $_GET['view_opl'];
        $list_comm = $_GET['list_comm'];
        $flags = $_GET['flags'];

        if ($flags == "1") {
            $query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, ALL_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
        } else {
            $query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
        }

        mysql_query($query) or die($query);

        break;


    case '209':

        $query = "select clients.id,clients.client_name, clients.email,clients.phone_city,clients.phone_mob, clients.unp, clients.num_doc, clients.acct, clients.code_bank
			from clients";

        $json = array();
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $json[] = array(
                'q' => "<a onclick=edit_cl('$row[0]')><span class='pull-right'><i class='glyphicon glyphicon-pencil'></i></<span></a>",
                'name' => $row[1],
                'unp' => $row[5],
                'mail' => $row[2],
                'phone1' => $row[3],
                'phone2' => $row[4],
                'dog' => $row[6],
                'rs' => $row[7],
                'bank' => $row[8],
            );
        }
        echo json_encode($json);

        break;

    case '210':
        $unp = $_GET['unp'];

        $flags = false;
        $query = "select * from clients where unp = " . $unp;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $flags = true;
        }

        if ($flags) {
            echo "1";
        } else {
            echo "0";
        }

        break;


    case '211':
        $id = $_GET['id'];
        $clientType = $_GET['clientType'];
        $clientName = addslashes($_GET['clientName']);
        $clientEmail = $_GET['clientEmail'];
        $clientPhoneMob = $_GET['clientPhoneMob'];
        $clientPhoneCity = $_GET['clientPhoneCity'];
        $clientAddressPost = $_GET['clientAddressPost'];
        $clientAddressDev = $_GET['clientAddressDev'];
        $clientNadb = $_GET['clientNadb'];
        $clientLim = $_GET['clientLim'];
        $clientTime = $_GET['clientTime'];
        $clientSize = $_GET['clientSize'];
        $dev_region_id = $_GET['dev_region_id'];
        $dev_post_city = $_GET['dev_post_city'];
        $dev_post_street = $_GET['dev_post_street'];
        $dev_post_kor = $_GET['dev_post_kor'];
        $dev_post_home = $_GET['dev_post_home'];
        $dev_post_kv = $_GET['dev_post_kv'];
        $dev_post_index = $_GET['dev_post_index'];
        $post_region_id = $_GET['post_region_id'];
        $post_post_city = $_GET['post_post_city'];
        $post_post_street = $_GET['post_post_street'];
        $post_post_kor = $_GET['post_post_kor'];
        $post_post_kv = $_GET['post_post_kv'];
        $post_post_home = $_GET['post_post_home'];
        $post_post_index = $_GET['post_post_index'];
        $post_post_raion = $_GET['post_post_raion'];
        $dev_post_raion = $_GET['dev_post_raion'];


        if ($clientType == 'f') {
            $clientSkype = $_GET['clientSkype'];
            $clientViber = $_GET['clientViber'];
            $clientUnp = '';
            $clientAcct = '';
            $clientBank = '';
            $clientCodeBank = '';
            $str = '';
            $num_doc = '';
            $osnov = '';
            $fio = '';
            $fio1 = '';
        }
        if ($clientType == 'u') {
            $clientSkype = '';
            $clientViber = '';
            $clientUnp = $_GET['clientUnp'];
            $clientAcct = $_GET['clientAcct'];
            $clientBank = addslashes($_GET['clientBank']);
            $clientCodeBank = $_GET['clientCodeBank'];
            $str = $_GET['str'];
            $num_doc = $_GET['num_doc'];
            $num_doc_m = $_GET['num_doc_m'];
            $num_dov = $_GET['num_dov'];
            $osnov = addslashes($_GET['osnov']);
            $fio = $_GET['fio'];
            $fio1 = $_GET['fio1'];

        }


        $query = "INSERT INTO clients  (CLIENT_NAME, EMAIL,CLIENT_STATUS, PHONE_MOB,PHONE_CITY,	
	CLIENT_SKYPE, CLIENT_VIBER, ADDRESS_POST, ADDRESS_DEV,
	UNP, ACCT, BANK, CODE_BANK,NADBAVKA,LIMITs,TIME_RAS,SIZE_PRE,Temp,num_doc,fio_dir,osn,fio_dir1,dover,
	post_region_id, post_post_city, post_post_street, post_post_kor, post_post_kv, post_post_index, dev_region_id, dev_post_city, dev_post_street, dev_post_kor, dev_post_kv,dev_post_index,post_house_num,dev_house_num,dev_raion,post_raion,num_doc_m) 
	VALUES(	'{$clientName}','{$clientEmail}','{$clientType}','{$clientPhoneMob}',
	'{$clientPhoneCity}','{$clientSkype}','{$clientViber}','{$clientAddressPost}',
	'{$clientAddressDev}','{$clientUnp}','{$clientAcct}','{$clientBank}','{$clientCodeBank}',
	'{$clientNadb}','{$clientLim}','{$clientTime}','{$clientSize}','{$str}','{$num_doc}','{$fio}','{$osnov}','{$fio1}','{$num_dov}'
	,'{$post_region_id}','{$post_post_city}','{$post_post_street}','{$post_post_kor}','{$post_post_kv}','{$post_post_index}','{$dev_region_id}'
	,'{$dev_post_city}','{$dev_post_street}','{$dev_post_kor}','{$dev_post_kv}','{$dev_post_index}','{$post_post_home}','{$dev_post_home}','{$dev_post_raion}','{$post_post_raion}','{$num_doc_m}');";


        if ($id != '') {
            $query = "UPDATE clients SET 
	CLIENT_NAME = '{$clientName}',
	EMAIL = '{$clientEmail}',
	CLIENT_STATUS = '{$clientType}',
	PHONE_MOB = '{$clientPhoneMob}',
	PHONE_CITY = '{$clientPhoneCity}',
	CLIENT_SKYPE = '{$clientSkype}',
	CLIENT_VIBER = '{$clientViber}',
	ADDRESS_POST = '{$clientAddressPost}',
	ADDRESS_DEV = '{$clientAddressDev}',
	UNP = '{$clientUnp}',
	ACCT = '{$clientAcct}',
	BANK = '{$clientBank}',
	CODE_BANK = '{$clientCodeBank}',
	NADBAVKA = '{$clientNadb}',
	LIMITs = '{$clientLim}',
	TIME_RAS = '{$clientTime}',
	SIZE_PRE = '{$clientSize}',
	Temp = '{$str}', 
	num_doc  = '{$num_doc}',
	fio_dir = '{$fio}',
	osn = '{$osnov}',
	fio_dir1 = '{$fio1}',
	dover = '{$num_dov}',
	post_region_id = '{$post_region_id}',
	post_post_city = '{$post_post_city}',
	post_post_street = '{$post_post_street}',
	post_post_kor = '{$post_post_kor}',
	post_post_kv = '{$post_post_kv}',
	post_post_index = '{$post_post_index}',
	dev_region_id = '{$dev_region_id}',
	dev_post_city = '{$dev_post_city}',
	dev_post_street = '{$dev_post_street}',
	dev_post_kor = '{$dev_post_kor}',
	dev_post_kv = '{$dev_post_kv}',
	dev_post_index = '{$dev_post_index}',
	post_house_num = '{$post_post_home}',
	dev_house_num = '{$dev_post_home}',
	dev_raion = '{$dev_post_raion}',
	post_raion = '{$post_post_raion}',
    num_doc_m = '{$num_doc_m}'               
	WHERE id='{$id}';";

        }


        mysql_query($query) or die($query);

        break;

    case '212':

        $id_ord = $_GET['id_ord'];
        $query = "select CLIENT_ID from orders where number =" . $id_ord;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $id_cl = $row[0];
        }

        $query = "select CLIENT_ID from orders where number =" . $id_ord;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $id_cl = $row[0];
        }

        /*Генерация кода отправки*/
        $parent_company = (isset($_GET['parent_company']) && !empty($_GET['parent_company'])) ? $_GET['parent_company'] : 1;
        $sett_track = ($parent_company == 2) ? '20' : '16';
        $sett_track_last = ($parent_company == 2) ? '21' : '18';

        $arr_round = array(8, 6, 4, 2, 3, 5, 9, 7);
        $query = "select val from settings where id = {$sett_track}; ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $last_num = $row[0];
        }

        $query = "select val from settings where id = {$sett_track_last}; ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $last_num_ = $row[0];
        }
        $last_num = (int)$last_num + 1;
        $l_flag = 0;
        if ($last_num > $last_num_) {
            $l_flag = 1;
        }
        $kol = strlen($last_num);
        while ($kol < 8) {
            $last_num = "0" . $last_num;
            $kol = strlen($last_num);
        }

        $mod11 = 0;
        for ($i = 0; $i < count($arr_round); $i++) {
            $mod11 += $arr_round[$i] * (int)$last_num[$i];
        }

        if ($mod11 <= 0)
            die(null);
        $mod11 = $mod11 % 11;
        $round_ = 11 - $mod11;
        if ($round_ > 10)
            $round_ = 5;
        if ($round_ == 10)
            $round_ = 0;

        $last_num = "PC" . $last_num . $round_ . "BY";
        $query = "select CLIENT_NAME, PHONE_MOB,post_post_index, post_post_kv, post_post_kor, post_post_street, post_post_city, post_region_id,post_house_num, ADDRESS_POST,PHONE_CITY, post_raion, email from clients where id =" . $id_cl;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            if ($l_flag > 0) {
                $last_num = '';
            }
            echo $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $row[6] . "|" . $row[7] . "|" . $row[8] . "|" . $row[9] . "|" . $row[10] . "|" . $last_num . "|" . "|" . $row[11] . "|" . $row[12] . "|";
        }


        break;

    case '213':

        $id_cl = $_GET['id_cl'];


        $query = "select
	ID, CLIENT_NAME, EMAIL, CLIENT_STATUS, PHONE_MOB, PHONE_CITY, CLIENT_SKYPE, CLIENT_VIBER, 
	ADDRESS_POST, ADDRESS_DEV, UNP, ACCT, BANK, CODE_BANK, NADBAVKA, LIMITs, TIME_RAS, SIZE_PRE, 
	temp, num_doc, fio_dir, osn, fio_dir1, mfo, dover, dev_post_index, dev_post_kv, dev_post_kor, 
	dev_post_street, dev_post_city, dev_region_id, post_post_index, post_post_kv, post_post_kor, 
	post_post_street, post_post_city, post_region_id, post_house_num, dev_house_num, dev_raion, post_raion, num_doc_m
	from clients  c where c.id =  " . $id_cl;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            echo $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $row[6] . "|" . $row[7] . "|" . $row[8] . "|" . $row[9] . "|" . $row[10] . "|" .
                $row[11] . "|" . $row[12] . "|" . $row[13] . "|" . $row[14] . "|" . $row[15] . "|" . $row[16] . "|" . $row[17] . "|" . $row[18] . "|" . $row[19] . "|" . $row[20] . "|" .
                $row[21] . "|" . $row[22] . "|" . $row[23] . "|" . $row[24] . "|" . $row[25] . "|" . $row[26] . "|" . $row[27] . "|" . $row[28] . "|" . $row[29] . "|" . $row[30] . "|" .
                $row[31] . "|" . $row[32] . "|" . $row[33] . "|" . $row[34] . "|" . $row[35] . "|" . $row[36] . "|" . $row[37] . "|" . $row[38] . "|" . $row[39] . "|" . $row[40] . "|" . $row[41];
        }


        break;


    case '214':

        $str = $_GET['str'];
        $json = "";

        $query = "select tip , name, raion, 'р-он', id from city where LOWER(name) like LOWER('" . $str . "%') ORDER BY name LIMIT 20";
        if ($str == "") {
            $json .= '<option ></option>';
            $query = "select tip , name, '', '', id from city where tip = 'г.' ORDER BY name";

        }
        // $json.= "<option ><input type='text' size = 60 id='postSearch' name='postSearch'   class='form-control'></option>";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $names = $row[0] . " " . $row[1];
            if ($row[2] != "") {
                $names .= "(" . $row[2] . " " . $row[3] . ")";
            }
            $json .= '<option value="' . $row[4] . '">' . $names . '</option>';
        }
        echo $json;

        break;


    case '215':

        $id = $_GET['id'];
        $json = "";


        $query = "select tip , name, obl, raion, id from city where id =" . $id;

        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $json .= $row[0] . " " . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|";
        }
        echo $json;

        break;

    case '216':

        $id_ord = $_GET['id_ord'];
        $query = "select CLIENT_ID from orders where number = (select order_id from order_product where id=" . $id_ord . ")";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $id_cl = $row[0];
        }

        /*Генерация кода отправки*/
        $query = "select val from settings where id = 16; ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $last_num = $row[0];
        }
        $last_num = (int)$last_num + 1;
        $last_num = "PC" . $last_num . rand(0, 9) . "BY";
        $query = "select CLIENT_NAME, PHONE_MOB,post_post_index, post_post_kv, post_post_kor, post_post_street, post_post_city, post_region_id,post_house_num, ADDRESS_POST,PHONE_CITY, post_raion from clients where id =" . $id_cl;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            echo $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $row[6] . "|" . $row[7] . "|" . $row[8] . "|" . $row[9] . "|" . $row[10] . "|" . $last_num . "|" . "|" . $row[11] . "|";
        }


        break;
    /*---------------------------217---------------------------*/
    case '217':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','1');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='2' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;
    case '218':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','1');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='20' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;

    /*---------------------------219---------------------------*/
    case '219':

        $id = $_GET['id'];
        $query = "select id,FIRM_NAME,unp from firms where id = " . $id;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            echo $row[1] . "|" . $row[2];
        }

        break;

    /*---------------------------220---------------------------*/
    case '220':

        $id = $_GET['id'];
        $query = "select ma.M_NAME, ma.M_UNIT, ma.M_SIZE, ma.M_PRICE,  ma.M_TOL, size_rez from material_attr ma where id =" . $id . " Limit 1";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            echo $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5];
        }

        break;

    /*---------------------------221---------------------------*/
    case '221':

        $nameMaterial_edit = $_GET['nameMaterial_edit'];
        $izmMaterial_edit = $_GET['izmMaterial_edit'];
        $sizeMaterial_edit = $_GET['sizeMaterial_edit'];
        $priceMaterial_edit = $_GET['priceMaterial_edit'];
        $tolMaterial_edit = $_GET['tolMaterial_edit'];
        $idMaterial = $_GET['idMaterial'];
        $eqFormat = $_GET['eqFormat'];


        $query = "UPDATE material_attr SET M_NAME = '{$nameMaterial_edit}', M_UNIT = '{$izmMaterial_edit}',M_SIZE = '{$sizeMaterial_edit}',M_PRICE = '{$priceMaterial_edit}',M_TOL = '{$tolMaterial_edit}',size_rez = '{$eqFormat}' WHERE  id = {$idMaterial};";
        mysql_query($query) or die($query);

        break;
    /*---------------------------222---------------------------*/
    case '222':
        $id1 = $_GET['id1'];

        $query = "UPDATE material_attr SET arh = 1 where FIND_IN_SET( ID,'" . $id1 . "')";
        mysql_query($query) or die($query);

    case '223':

        $id1 = $_GET['id1'];
        $query = "UPDATE order_product SET status='4' WHERE  FIND_IN_SET(ID , '" . $id1 . "') ";
        mysql_query($query) or die($query);
        break;
    case '224':

        $id = $_GET['id'];
        $query = "select o.CLIENT_ID, o.ORDER_NUM, o.OST_SUM,o.ALL_SUM, o.DATE_, o.view_opl, o.Comments from oplati o where o.id =" . $id;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            echo $row[0] . "|" . $row[1] . "|" . $row[2] . "|" . $row[3] . "|" . $row[4] . "|" . $row[5] . "|" . $row[6];
        }

        break;


    /*Сохранение оплат*/
    case '225':


        $id = $_GET['id'];
        $client = $_GET['client'];
        $num = $_GET['num'];
        $sum = $_GET['sum'];
        $sum = str_replace(",", ".", $sum);
        $sum = round($sum, 2);
        $date_ = $_GET['date'];
        $view_opl = $_GET['view_opl'];
        $list_comm = $_GET['list_comm'];
        $flags = $_GET['flags'];

        if ($id == 0 or $id == "0") {
            if ($flags == "1") {
                $query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, ALL_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
            } else {
                $query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
            }
        } else {
            if ($flags == "1") {
                $query = "	UPDATE oplati SET 
	CLIENT_ID = '{$client}',
	ORDER_NUM = '{$num}', 
	ALL_SUM   = '{$sum}', 
	DATE_     = '{$date_}', 
	view_opl  = '{$view_opl}', 
	Comments  = '{$list_comm}'
	WHERE  id = '{$id}';";

            } else {
                $query = "	UPDATE oplati SET 
	CLIENT_ID = '{$client}',
	ORDER_NUM = '{$num}', 
	OST_SUM   = '{$sum}', 
	DATE_     = '{$date_}', 
	view_opl  = '{$view_opl}', 
	Comments  = '{$list_comm}'
	WHERE  id = '{$id}';";

            }
        }

        mysql_query($query) or die($query);

        break;

    case '226':

        $id = $_GET['id'];
        $dt = $_GET['dt'];

        $query = "UPDATE orders SET DATE_OR='{$dt}' WHERE  NUMBER= " . $id;
        mysql_query($query) or die($query);
        break;

    case '227':
        $flags = false;
        $unp = $_GET['unp'];
        $query = "select count(*) from firms where unp = " . $unp;
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {
            $flags = $row[0];
        }

        echo $flags;

        break;

    /*---------------------------228---------------------------*/
    case '228':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','12');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='12' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;

    case '228_1':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','1');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='1' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;

    case '229':
        $phone = $_GET['phone'];

        $flags = false;
        if ($phone != '+375') {
            $query = "select * from clients where PHONE_MOB = " . $phone;
            $result = mysql_query($query) or die($query);
            while ($row = mysql_fetch_row($result)) {
                $flags = true;
            }
        }

        if ($flags) {
            echo "1";
        } else {
            echo "0";
        }

        break;

    /*---------------------------230---------------------------*/
    case '230':

        $id = $_GET['id'];
        $query = "select status,id from order_product where FIND_IN_SET( id , '" . $id . "') ";
        $result = mysql_query($query) or die($query);
        while ($row = mysql_fetch_row($result)) {

            $orderDate = date("Y-m-d H:i:s");
            $status = $row[0];
            $id1 = $row[1];
            $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id1}','{$login}','{$orderDate}','{$status}','12');";
            mysql_query($query2) or die($query2);


            $query1 = "UPDATE order_product SET status='20' WHERE  ID=" . $row[1];
            //	echo $query1 ."<br>";
            mysql_query($query1) or die($query1);

            $query2 = "UPDATE lock_task SET flags='0' WHERE  id_prod=" . $row[1] . " AND users = '" . $login . "' AND oper = " . $status;

            mysql_query($query2) or die($query2);

        }
        break;

    /*---------------------------231---------------------------*/
    case '231':
        $id = $_GET['orderClient'];
        $parent_company = empty($_GET['parent_company']) ? 1 : $_GET['parent_company'];
        $msg = '';

        do {
            if (empty($id) || empty($parent_company)) {
                $msg = 1;
            }

            $nd = ($parent_company == 2) ? 'clients.num_doc_m' : 'clients.num_doc';

            $query = "SELECT " . $nd . "
						FROM clients
						WHERE clients.ID=" . $id . " AND clients.CLIENT_STATUS='u'";

            $result = mysql_query($query) or die($query);
            if ($row = mysql_fetch_row($result)) {
                if (empty(trim($row[0]))) {
                    $msg = 2;
                }
            }

        } while (false);

        echo $msg;
        break;

    /*---------------------------232---------------------------*/
    case '232':
        $id = $_GET['orderClient'];
        $parent_company = $_GET['parent_company'];
        $num_doc = $_GET['num_doc'];
        $msg = '';

        do {
            if (empty($id) || empty($parent_company) || empty($num_doc)) {
                $msg = 1;
            }
            $nd = ($parent_company == 2) ? 'clients.num_doc_m' : 'clients.num_doc';

            $query = "UPDATE clients SET " . $nd . "='" . $num_doc . "' WHERE ID=" . $id;
            mysql_query($query) or die($query);

        } while (false);

        echo $msg;

        break;

    default:
        echo '';

}


?>