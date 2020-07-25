<?php	
	function number2string($number) {
		//округляем число до 2х знаков
		$number = ceil(floatval($number) * 100) / 100;
		//делим на рубли и копейки
		$cent = 0;
		$number = strval($number);
		str_replace($number, ",", ".");
		if(strpos(strval($number), '.') > 0) {
			$rub = substr($number, 0, strpos(strval($number), '.'));
			$kop = substr($number, strpos(strval($number), '.') + 1);
			$number = $rub;
			$cent = $kop;
		}
		$number = intval($number);
		// обозначаем словарь в виде статической переменной функции, чтобы 
		// при повторном использовании функции его не определять заново
		static $dic = array(
		
			// словарь необходимых чисел
			array(
				-2	=> 'две',
				-1	=> 'одна',
				0	=> 'ноль',
				1	=> 'один',
				2	=> 'два',
				3	=> 'три',
				4	=> 'четыре',
				5	=> 'пять',
				6	=> 'шесть',
				7	=> 'семь',
				8	=> 'восемь',
				9	=> 'девять',
				10	=> 'десять',
				11	=> 'одиннадцать',
				12	=> 'двенадцать',
				13	=> 'тринадцать',
				14	=> 'четырнадцать' ,
				15	=> 'пятнадцать',
				16	=> 'шестнадцать',
				17	=> 'семнадцать',
				18	=> 'восемнадцать',
				19	=> 'девятнадцать',
				20	=> 'двадцать',
				30	=> 'тридцать',
				40	=> 'сорок',
				50	=> 'пятьдесят',
				60	=> 'шестьдесят',
				70	=> 'семьдесят',
				80	=> 'восемьдесят',
				90	=> 'девяносто',
				100	=> 'сто',
				200	=> 'двести',
				300	=> 'триста',
				400	=> 'четыреста',
				500	=> 'пятьсот',
				600	=> 'шестьсот',
				700	=> 'семьсот',
				800	=> 'восемьсот',
				900	=> 'девятьсот'
			),
			
			// словарь порядков со склонениями для плюрализации
			array(
				array('рубль', 'рубля', 'рублей'),
				array('тысяча', 'тысячи', 'тысяч'),
				array('миллион', 'миллиона', 'миллионов'),
				array('миллиард', 'миллиарда', 'миллиардов'),
				array('триллион', 'триллиона', 'триллионов'),
				array('квадриллион', 'квадриллиона', 'квадриллионов'),
				// квинтиллион, секстиллион и т.д.
			),
			
			// карта плюрализации
			array(
				2, 0, 1, 1, 1, 2
			),
			//копейки отдельно
			array('копейка', 'копейки', 'копеек')
		);
		
		// обозначаем переменную в которую будем писать сгенерированный текст
		$string = array();
		
		// дополняем число нулями слева до количества цифр кратного трем,
		// например 1234, преобразуется в 001234
		//для дроби доваляем до 100х
		$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
		$cent = str_pad($cent, ceil(strlen($cent)/2)*2, 0, STR_PAD_RIGHT);
		// разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
		// т.к. мы не знаем максимальный порядок числа и будем бежать снизу
		// единицы, тысячи, миллионы и т.д.
		$parts = array_reverse(str_split($number,3));
		
		if(intval($number) == 0) {
			array_push($string, $dic[0][0]);
			array_push($string, $dic[1][0][2]);
		}
		else {			
			// бежим по каждой части
			foreach($parts as $i=>$part) {
				
				// если часть не равна нулю, нам надо преобразовать ее в текст
				if($part>0) {
					
					// обозначаем переменную в которую будем писать составные числа для текущей части
					$digits = array();
					
					// если число треххзначное, запоминаем количество сотен
					if($part>99) {
						$digits[] = floor($part/100)*100;
					}
					
					// если последние 2 цифры не равны нулю, продолжаем искать составные числа
					if($mod1=$part%100) {
						$mod2 = $part%10;
						$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
						if($mod1<20 || !$mod2) {
							$digits[] = $flag*$mod1;
						} else {
							$digits[] = floor($mod1/10)*10;
							$digits[] = $flag*$mod2;
						}
					}
					
					// берем последнее составное число, для плюрализации
					$last = abs(end($digits));
					
					// преобразуем все составные числа в слова
					foreach($digits as $j=>$digit) {
						$digits[$j] = $dic[0][$digit];
					}
					
					// добавляем обозначение порядка или валюту
					$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
					
					// объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
					array_unshift($string, join(' ', $digits));
				}
			}
		}
		
		//преобразуем копейки
		array_push($string, $cent);
		$cent = intval($cent);
		$cent_1 = floor($cent/10) * 10;
		if($cent_1 >= 20) {
			$cent_2 = $cent%$cent_1;
			if($cent_2 > 0) {
				if($cent_2 == 1)
					array_push($string, $dic[3][0]);
				else if($cent_2 < 5)
					array_push($string, $dic[3][1]);
				else
					array_push($string, $dic[3][2]);
			}
			else
				array_push($string, $dic[3][2]);
		}
		else {
			if($cent < 5) {
				if($cent == 1)
					array_push($string, $dic[3][0]);
				else
					array_push($string, $dic[3][1]);
			}
			else
				array_push($string, $dic[3][2]);
		}
		
		// преобразуем переменную в текст и возвращаем из функции, ура!
		return join(' ', $string);
	}
	
	//функция скачивания файла
	function file_force_download($file) {
		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			  ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			exit;
		}
    }
	
	//вывод объекта для теста
	function vardump($var) {
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}

	//возврат из справочника ламинации
	function returnLaminat($id) {
		$directory = array(
			array('value'=>1,'name'=>"гл.+гл."),
			array('value'=>2,'name'=>"мат+мат"),
			array('value'=>3,'name'=>"гл.+0"),
			array('value'=>4,'name'=>"мат+0"),
			array('value'=>5,'name'=>"цифра"),
			array('value'=>6,'name'=>"гл.+мат"),
			array('value'=>7,'name'=>"СофтТач+0"),
			array('value'=>8,'name'=>"СофтТач+СофтТач"),
			array('value'=>9,'name'=>"СофтТач+мат"),
		);
		
		$return = "";
		foreach($directory as $dir) {
			if($dir['value'] == $id) {
				$return = $dir['title'];
				break;
			}
		}
		
		return $return;
	}
	
	//возврат из справочника диаметров
	function returnDiam($id) {
		$directory = array(
			array('value'=>1,'name'=>"3 мм"),
			array('value'=>2,'name'=>"4 мм"),
			array('value'=>3,'name'=>"6 мм"),
			array('value'=>4,'name'=>"5 мм"),
		);
		
		$return = "";
		foreach($directory as $dir) {
			if($dir['value'] == $id) {
				$return = $dir['title'];
				break;
			}
		}
		
		return $return;
	}
	
	//возврат из справочника раскраски люверсов
	function returnColorLuv($id) {
		$directory = array(
			array('value'=>1,'name'=>"4 мм серебро"),
			array('value'=>2,'name'=>"4 мм золото"),
			array('value'=>3,'name'=>"4 мм черный"),
			array('value'=>4,'name'=>"5 мм золото"),
			array('value'=>5,'name'=>"5 мм серебро"),
		);
		
		$return = "";
		foreach($directory as $dir) {
			if($dir['value'] == $id) {
				$return = $dir['title'];
				break;
			}
		}
		
		return $return;
	}
	
	//функции
	function fun_group($id) {		
		$result = mysql_query("SELECT kl.ID, kl.title, kl.flags , kl.parent  FROM  kl_mat kl WHERE kl.ID = " . $id);
		while ($cat = mysql_fetch_array($result)) {	
			$name = $cat["title"];
			$id_pr = $cat["parent"];
			$flags = $cat["flags"];
			
			while ($flags == "0") {
				$names = fun_name($id_pr);
				$name = $names;
				$flags = fun_flag($id_pr);
				$id_new = fun_parent($id_pr);
				$id_pr = $id_new;
			}
		}
		
		return $name;
	}
	
	function fun_names($id) {
		$result = mysql_query("SELECT kl.ID, kl.title, kl.flags , kl.parent  FROM  kl_mat kl WHERE kl.ID = " . $id);
		while ($cat = mysql_fetch_array($result)) {		
			$name = $cat["title"];
			$id_pr = $cat["parent"];
			$flags = $cat["flags"];
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
	
	function fun_name($id) {
		$result = mysql_query("select title from kl_mat where id =" . $id);
		if ($cat = mysql_fetch_array($result)) {
			return $cat["title"];
		}
	}
	
	function fun_flag($id) {
		$result = mysql_query("select flags from kl_mat where id =" . $id);
		if ($cat = mysql_fetch_array($result)) {
			return $cat["flags"];
		}
	}
	
	function fun_parent($id) {
		$result = mysql_query("select parent from kl_mat where id =" . $id);
		if ($cat = mysql_fetch_array($result)) {
			return $cat["parent"];
		}
	}
	
?>