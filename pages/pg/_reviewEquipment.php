<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title></title>
    <!--   <link href="css/bootstrap.min.css" rel="stylesheet">
       <link href="css/justified-nav.css" rel="stylesheet">
       <script src="js/bootstrap.min.js" type = "text/javascript"></script>
       <script src="js/jquery.min.js"></script>
       <script src="js/funJs.js"></script>
       <script src="js/bootstrap.js"></script> -->

    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <script src="../../vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="../../vendor/bootstrap/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
    <script>
        /* window.onblur = function () {window.close()}*/
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#optgroup').multiselect({
                enableClickableOptGroups: true,
                enableFiltering: true
            });
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#selOper').multiselect({
                enableClickableOptGroups: true
            });
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#eqFormat').multiselect({
                enableClickableOptGroups: true
            });
        });

    </script>
</head>
<body>
<?php


function fun_name($id)
{
    $result = mysql_query("select title from kl_mat where id =" . $id);
    while ($cat = mysql_fetch_row($result)) {
        return $cat[0];
    }
}


function fun_flag($id)
{
    $result = mysql_query("select flags from kl_mat where id =" . $id);
    while ($cat = mysql_fetch_row($result)) {
        return $cat[0];
    }
}

function fun_parent($id)
{
    $result = mysql_query("select parent from kl_mat where id =" . $id);
    while ($cat = mysql_fetch_row($result)) {
        return $cat[0];
    }
}

function fun_names($id)
{

    $result = mysql_query("SELECT kl.ID, kl.title, kl.flags , kl.parent  FROM  kl_mat kl WHERE kl.ID = " . $id);
    while ($cat = mysql_fetch_row($result)) {

        $name = $cat[1];
        $id_pr = $cat[3];
        $flags = $cat[2];
        while ($flags == "0") {
            $names = fun_name($id_pr);
            $name = $names . " " . $name;
            $flags = fun_flag($id_pr);

            $id_new = fun_parent($id_pr);
            $id_pr = $id_new;
        }
    }

    return $name;
}


function build_tree($cats, $parent_id, $strr, $only_parent = false)
{

    if (is_array($cats) and isset($cats[$parent_id])) {


        if ($only_parent == false) {
            foreach ($cats[$parent_id] as $cat) {
                if (count($cats[$cat['id']]) > 0) {

                    $tree .= build_tree($cats, $cat['id'], $strr);
                } else {
                    $flagss = 0;
                    for ($z = 0; $z < count($strr); $z++) {
                        if ($cat['id'] == $strr[$z]) {
                            $tree .= "<option value = '" . $cat['id'] . "' selected>" . fun_names($cat['id']);
                            $flagss = 1;
                        }
                    }
                    if ($flagss == 0) {
                        $tree .= "<option value = '" . $cat['id'] . "' >" . fun_names($cat['id']);
                    }
                    $tree .= build_tree($cats, $cat['id'], $strr);
                    $tree .= "</option>";
                }

            }
        }
    } else return null;
    return $tree;
}


$flag = true;
if (!empty($_GET["id_Equipment"])) {
    include_once '../db.php';
    $query = "select * from equipment where id =" . $_GET["id_Equipment"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) {
        $flag = $line[16];
        $ca = $line[14];
        $ca1 = $line[15];
        $ca2 = $line[4];
        print "<div class='container'>";
        print "<div class='masthead'>";
        print "    <h3 class='text-muted'>Просмотр информации об оборудование</h3><hr/>";
        print "    <form  class='form-signin' method='post' action='_updateEquipment.php'>";
        if ($flag == '1') {
            print "<div class='checkbox'><label><input type='checkbox' name='priceTest' id='priceTest'  checked > По умолчанию</label></div>  ";
        } else {
            print "<div class='checkbox'><label><input type='checkbox' name='priceTest' id='priceTest' > По умолчанию</label></div>  ";
        }

        if ($line[17] == '1') {
            print " 	<div class='checkbox'><label><input type='checkbox' name='offTest' id='offTest' checked>Подрядчик</label></div>  ";
        } else {
            print "<div class='checkbox'><label><input type='checkbox' name='offTest' id='offTest' > Подрядчик</label></div>  ";
        }


        print "        	<label>Наименование:</label> <input type='text' name='eqName' id ='eqName' value='$line[1]'><br/>  ";
        print "			<label>Макс. размер: </label> <input type='text' name='eqMaxSize' id='eqMaxSize' value='$line[2]'><br/>   ";
        print "			<label>Мин. размер:</label> <input type='text' name='eqMinSize' id='eqMinSize' value='$line[3]'><br/>  	 ";
//print "			<label>Используемые форматы: </label> <input type='text' name='eqFormat' id='eqFormat' value='$line[4]'><br/>   "; 


        print "	<label>
				Используемые форматы
				<select  id = 'eqFormat'  data-style='btn-default' multiple  name='eqFormat[]' >";


        include "../db.php";
        $query = "select o.id, o.size from size_print o ";
        $result = mysql_query($query) or die("Query failed");
        $prod_idd = explode(",", $ca2);
        while ($row = mysql_fetch_row($result)) {
            $flagss = 0;
            for ($z = 0; $z < count($prod_idd); $z++) {
                if ($row[0] == $prod_idd[$z]) {
                    if ($row[3] != "") {
                        print "<option value='$row[0]' selected>" . $row[1] . "</option>";
                    } else {
                        print "<option value='$row[0]' selected>" . $row[1] . "</option>";
                    }

                    $flagss = 1;
                }
            }
            if ($flagss == 0) {
                if ($row[3] != "") {
                    print "<option value='$row[0]'>" . $row[1] . "</option>";
                } else {
                    print "<option value='$row[0]'>" . $row[1] . "</option>";
                }

            }

        }

        print "	</select>	
		</label><br/> ";


        print "			<label>Ед. изм.:</label> <input type='text' name='eqUnit' id='eqUnit' value='$line[5]'><br/>   ";
        print "			<label>Кол-во операций, мин. (час): </label> <input type='text' name='eqKolOper' id='eqKolOper' value='$line[6]'><br/>   ";
        print "			<label>Время приладки, мин. (час):</label> <input type='text' name='eqMakeReadyTime' id='eqMakeReadyTime' value='$line[7]'><br/>   ";
        print "			<label>Аммортизация оборудования: </label> <input type='text' name='eqAmmor' id='eqAmmor' value='$line[8]'><br/>  ";
        print "			<label>Срок аммортизации, мес.:</label> <input type='text' name='eqSrokAmmor' id='eqSrokAmmor' value='$line[9]' ><br/>  ";
        print "			<label>Стоимость оборудования: </label> <input type='text' name='eqPriceOper' id='eqPriceOper' value='$line[10]'><br/>   ";
        print "			<label>Занимаемая площадь, м.кв.:</label> <input type='text' name='eqSq' id='eqSq' value='$line[11]'><br/>  ";
        print "			<label>Стоимость аренды, за кв.м.: </label> <input type='text' name='eqPriceArenda' id='eqPriceArenda' value='$line[12]'><br/>   ";
        print "			<label>Стоимость:</label> <input type='text' name='eqPriceArenda' id='eqPriceArenda' value='$line[13]' disabled><br/>   ";
        print "					<label>Отступ справо и слево:</label> <input type='text' size = '5' name='ladnr' id = 'ladnr'  value='$line[18]' d><br/>";
        print "			<label>Отступ сверху и снизу::</label> <input type='text' size = '5' name='uandd' id = 'uandd'  value='$line[19]' d><br/>";
        print "<label>Надбавка max %:</label> <input type='text' size = '5' name='nadb_max' id = 'nadb_max' value = '$line[20]' ><br/>";
        print "		<label>Кол-во min:</label> <input type='text' size = '5' name='total_min' id = 'total_min' value = '$line[23]' ><br/>";
        print "	<label>Надбавка min %:</label> <input type='text' size = '5' name='nadb_min' id = 'nadb_min' value = '$line[21]' ><br/>";
        print "	<label>Кол-во max:</label> <input type='text' size = '5' name='total_max' id = 'total_max' value = '$line[22]' ><br/>";
        print "        <input type='hidden' value='$line[0]' id='eqId' name='eqId'> <br/>";
    }

}
?>

<label>
    Операция
    <select id='selOper' data-style="btn-default" multiple name='selOper[]' style='height: 300px;width 300px'>

        <?php
        include "../db.php";
        $query = "select o.id, o.operation_name, o.par, o.comments  from operations o where operation_name  is not null ORDER BY operation_name";
        $result = mysql_query($query) or die("Query failed");
        $prod_idd = explode(",", $ca1);
        while ($row = mysql_fetch_row($result)) {
            $flagss = 0;
            for ($z = 0; $z < count($prod_idd); $z++) {
                if ($row[0] == $prod_idd[$z]) {
                    if ($row[3] != "") {
                        print "<option value='$row[0]' selected>" . $row[1] . " " . $row[2] . "(" . $row[3] . ")" . "</option>";
                    } else {
                        print "<option value='$row[0]' selected>" . $row[1] . " " . $row[2] . "</option>";
                    }

                    $flagss = 1;
                }
            }
            if ($flagss == 0) {
                if ($row[3] != "") {
                    print "<option value='$row[0]'>" . $row[1] . " " . $row[2] . "(" . $row[3] . ")" . "</option>";
                } else {
                    print "<option value='$row[0]'>" . $row[1] . " " . $row[2] . "</option>";
                }

            }

        }
        ?>
    </select>
</label>

<div class='row'>
    <div class='col-md-1'>
        <div class='block1'><label>Материалы</label></div>
    </div>
    <div class='col-md-6'>
        <div class='block2'>
            <select id='optgroup' data-style="btn-default" multiple name='optgroup[]' style='height: 300px;width 300px'>
                <?php

                $result = mysql_query("SELECT * FROM  kl_mat  order by parent, title");
                //Если в базе данных есть записи, формируем массив
                if (mysql_num_rows($result) > 0) {
                    $cats = array();
                    $tq = "";
                    //В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
                    while ($cat = mysql_fetch_assoc($result)) {
                        $cats_ID[$cat['id']][] = $cat;
                        $cats[$cat['parent']][$cat['id']] = $cat;
                    }

                    $result = mysql_query("select id, parent from kl_mat where flags = 1 and parent <> 0 ORDER BY parent");
                    $prod_idd = explode(",", $ca);
                    while ($cad = mysql_fetch_assoc($result)) {

                        $results = mysql_query("select title from kl_mat where id = " . $cad['id']);
                        while ($cads = mysql_fetch_assoc($results)) {
                            echo "<optgroup label = '" . $cads['title'] . "'> ";
                            echo build_tree($cats, $cad['id'], $prod_idd);
                            echo "</optgroup > ";
                        }
                    }

                }

                echo "</select></td>";


                ?>
        </div>
    </div>
</div>
<br/><br/>
<?
print "			<input type='button' id = 'buttonWalker' value='Изменить' onclick=";
print '"tabWalker(';
print "'eqId','dynamic','eqName','eqMaxSize','eqMinSize','eqUnit','eqKolOper','eqMakeReadyTime','eqAmmor','eqSrokAmmor','eqPriceOper','eqSq','eqPriceArenda')";
print '"/>';
print "    </form>";
print "</div>";
print "</div>";

?>
</body>
</html>

<script>
    function addOper(tableID, selOper) 								 // функция для добавления строки к части шаблона
    {

        var tbl = document.getElementById(tableID);                   // таблица, с которой работаем
        var rws = tbl.rows;                                            // коллекция существующих строк таблицы
        var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
        var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
        //console.log(cls);
        var ro = tbl.insertRow(-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
        var selOper = document.getElementById(selOper).value;
        var ce = ro.insertCell(-1);
        ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";

        ce = ro.insertCell(-1);
        ce.innerHTML = selOper;

        document.getElementById('selOper').selectedIndex = '';

        // по завершению цикла, когда ВСЕ ячейки получат содержимое, ОБРАЗ добавленной строки превратится в РЕАЛЬНУЮ строку
    }
</script>

<script>
    function del_row() //удаление строк
    {
        var nodeList = document.getElementsByName('chDel');
        var array = Array.prototype.slice.call(nodeList);
        for (var i = 0; i < array.length; i++) {
            if (array[i].checked) {
                var tr = array[i].parentNode.parentNode;
                var parent = tr.parentNode;
                parent.removeChild(tr);
            }
        }
    }
</script>

<script>

    function tabWalker(eqId, tableID, eqName, eqMaxSize, eqMinSize, eqUnit, eqKolOper, eqMakeReadyTime, eqAmmor, eqSrokAmmor, eqPriceOper, eqSq, eqPriceArenda) 				//функция построчного чтения таблицы, с последующим добавлением в базу
    {
        var str = "";

        var t = 0;
        var idEquipment = document.getElementById(eqId).value;
        var eqName = document.getElementById(eqName).value;
        var eqMaxSize = document.getElementById(eqMaxSize).value;
        var eqMinSize = document.getElementById(eqMinSize).value;
        //var eqFormat=document.getElementById(eqFormat).value;
        var eqUnit = document.getElementById(eqUnit).value;
        var eqKolOper = document.getElementById(eqKolOper).value;
        var eqMakeReadyTime = document.getElementById(eqMakeReadyTime).value;
        var eqAmmor = document.getElementById(eqAmmor).value;
        var eqSrokAmmor = document.getElementById(eqSrokAmmor).value;
        var eqPriceOper = document.getElementById(eqPriceOper).value;
        var eqSq = document.getElementById(eqSq).value;
        var eqPriceArenda = document.getElementById(eqPriceArenda).value;
        var uandd = document.getElementById('uandd').value;
        var ladnr = document.getElementById('ladnr').value;
        var nadb_max = document.getElementById('nadb_max').value;
        var total_min = document.getElementById('total_min').value;
        var nadb_min = document.getElementById('nadb_min').value;
        var total_max = document.getElementById('total_max').value;
        //console.log(idEquipment);

        var optgroup1 = document.getElementById('selOper');
        var selMat1 = optgroup1.options.length;

        var SelectElements2 = new Array;
        var y = 0;

        for (var n = 0; n < selMat1; n++) {
            if (optgroup1.options[n].selected == true && !SelectElements2.includes(optgroup1.options[n].value)) {
                SelectElements2[y] = optgroup1.options[n].value;
                y++;
            }
        }
        current2 = SelectElements2;

        var optgroup = document.getElementById('optgroup');
        var selMat = optgroup.options.length;

        var SelectElements = new Array;
        var y = 0;

        for (var n = 0; n < selMat; n++) {
            if (optgroup.options[n].selected == true && !SelectElements.includes(optgroup.options[n].value)) {
                SelectElements[y] = optgroup.options[n].value;
                y++;
            }
        }
        current = SelectElements;


        var optgroup1 = document.getElementById('eqFormat');
        var selMat1 = optgroup1.options.length;

        var SelectElements2 = new Array;
        var y = 0;

        for (var n = 0; n < selMat1; n++) {
            if (optgroup1.options[n].selected == true && !SelectElements2.includes(optgroup1.options[n].value)) {
                SelectElements2[y] = optgroup1.options[n].value;
                y++;
            }
        }
        eqFormat = SelectElements2;

        priceTest = 0;
        if (document.getElementById('priceTest').checked) {
            priceTest = 1;
        }

        offTest = 0;
        if (document.getElementById('offTest').checked) {
            offTest = 1;
        }

        var post = '_updateEquipment.php?idEquipment=' + idEquipment + '&eqName=' + eqName + '&eqMaxSize=' + eqMaxSize + '&eqMinSize=' + eqMinSize + '&eqFormat=' + eqFormat + '&eqUnit=' + eqUnit + '&eqKolOper=' + eqKolOper + '&eqMakeReadyTime=' + eqMakeReadyTime + '&eqAmmor=' + eqAmmor + '&priceTest=' + priceTest + '&eqSrokAmmor=' + eqSrokAmmor + '&offTest=' + offTest + '&eqPriceOper=' + eqPriceOper + '&ladnr=' + ladnr + '&uandd=' + uandd + '&eqSq=' + eqSq + '&eqPriceArenda=' + eqPriceArenda + '&eqStr=' + current2 + '&current=' + current + '&nadb_max=' + nadb_max + '&total_min=' + total_min + '&nadb_min=' + nadb_min + '&total_max=' + total_max;
        window.location.href = post;
    }

    //window.onload=tabWalker;

</script>


<script>
    function fui() {
        if (document.getElementById('priceTest').checked) {
            var idEquipment = document.getElementById('eqId').value;
            $.ajax({
                type: "GET",
                url: 'chek_eq.php',
                data: {
                    id: idEquipment
                }, success: function (data) {//возвращаемый результат от сервера

                    if (data != '0') {
                        document.getElementById('priceTest').checked = false;
                        alert("Не возможно!!!!!! Уже выбрано оборудование по умолчанию!!!!!")

                    }
                }
            });
        }
    }


</script> 
  
  