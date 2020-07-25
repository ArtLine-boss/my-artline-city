<?php
    //загрузка констант
    $dir = $_SERVER['DOCUMENT_ROOT'];
    if(file_exists($dir . '/www/includes/config.php'))
        require_once ($dir . '/www/includes/config.php');
    if(file_exists($dir . '/www/includes/constants.php'))
        require_once ($dir . '/www/includes/constants.php');
    if(file_exists($dir . '/www/includes/accesses.php'))
        require_once ($dir . '/www/includes/accesses.php');

    //загрузка всех стандартных классов
    function __autoload($classname)
    {
    	$dir = $_SERVER['DOCUMENT_ROOT'];
		$classname = str_replace('_', '/', $classname);
		$filename = $dir . "/www/" . $classname . ".php";
		if(file_exists($filename))
			include_once($filename);
    }

    class API
	{
		/**
		 * Перевод текста
		 * @param $txt - текст (ключ) на английском
		 * @return string - строка на русском
		 */
		static function __($txt) {
			$result = $txt;

			do {
				$lang = new core_langues();
				$result = $lang->get($txt);
			} while(false);

			return $result;
		}

		//вывод объекта для теста
		static function vardump($object, $die = true)
		{
			print '<pre>';
			print_r($object);
			if ($die) die();
		}

		/**
		 * создание таблицы с данными
		 * $field - массив вида ключ в $data - наименование... например [0] => array('key' => 'name')
		 */
		static function viewTable($data = array(), $field = array(), $id = null, $limitName = '', $page = '')
		{
			$table = '';

			do {
				if (!is_array($data) || !is_array($field))
					break;
				if (count($field) === 0)
					break;
				// массив количества записей на одной странице
				$aLimits = [1, 2, 5, 10, 25, 50];
				// текущее значения количества записей на странице
				$current_limit = !empty($limitName) && isset($_GET[$limitName]) && !empty($_GET[$limitName]) ? intval($_GET[$limitName]) : 25;
				// текущая страница
				$current_page = !empty($page) && isset($_GET[$page]) && !empty($_GET[$page]) ? intval($_GET[$page]) : 1;
				// удаляем из запроса количество записей и номер страницы
				if(!empty($limitName) && isset($_GET[$limitName]) && !empty($_GET[$limitName])) {
					unset($_GET[$limitName]);
				}
				if(!empty($page) && isset($_GET[$page]) && !empty($_GET[$page])) {
					unset($_GET[$page]);
				}
				// для лимита строим редирект
				$href = $_SERVER['SCRIPT_NAME'] . '?';
				$href_tmp = "";
				// определяем максимальное количество страниц
				$count_pages = ceil(count($data) / $current_limit);
				if($count_pages > 0) {
					if($current_page > $count_pages) {
						$current_page = $count_pages;
					}
					$min_element = ($current_page - 1) * $current_limit;
					$max_element = $current_page * $current_limit - 1;
					foreach ($data as $dk => $dv) {
						if($dk < $min_element || $dk > $max_element) {
							unset($data);
						}
					}
				} else {
					$current_page = 1;
				}

				foreach ($_GET as $gk => $gv) {
					$href_tmp .= (!empty($href_tmp) ? "&" : "") . $gk . '=' . $gv;
				}
				$href_limit = $href . $href_tmp . (!empty($page) ? '&' . $page . '=' . $current_page : '');
				$href_page = $href . $href_tmp . (!empty($limitName) ? '&' . $limitName . '=' . $current_limit : '');
				$table .= '<div class="c-table-responsive@desktop">';
				if(!empty($limitName)) {
					$table .= 'Показывать <select onchange="redirectTable(\'' . $href_limit . '\', \'' . $limitName . '\', this.value)">';
					foreach ($aLimits as $lk => $lv) {
						$table .= '<option value="' . $lv . '"' . ($current_limit == $lv ? ' selected' : '') . '>' . $lv . '</option>';
					}
					$table .= '</select> записей';
				}
				$table .= '<table class="c-table"' . ((empty($id)) ? 'id="data_table"' : ' id="' . $id . '"') . '">';
				$table .= '<thead class="c-table__head c-table__head--slim"><tr class="c-table__row">';
				foreach ($field as $k => $v) {
					if (!is_array($v))
						continue;
					$table .= '<th class="c-table__cell c-table__cell--head sort"';
					if (count($v) > 1) {
						$table .= " data-json='" . json_encode($v) . "'>";
					} else {
						foreach ($v as $_k => $_v) {
							$table .= ' id="' . $_k . '">';
							$table .= $_v;
							break;
						}
					}
					$table .= '</th>';
				}
				$table .= '</tr></thead>';
				$table .= '<tbody>';
				foreach ($data as $k => $v) {
					$table .= '<tr class="c-table__row">';
					foreach ($field as $_k => $_v) {
						$table .= '<td class="c-table__cell">';
						foreach ($_v as $__k => $__v) {
							$table .= $v->$__k;
							break;
						}
						$table .= '</td>';
					}
					$table .= '</tr>';
				}
				$table .= '</tbody>';
				$table .= '</table>';
				// навигация
				if(!empty($page)) {
					$table .= '<nav class="c-pagination u-justify-center"><ul class="c-pagination__list" id="nav_' . $page . '">';
					$start = ($current_page - 3) < 1 ? 1 : ($current_page - 3);
					$end = ($current_page + 3) > $count_pages ? $count_pages : ($current_page + 3);
					$end = empty($end) ? 1 : $end;
					$table .= $current_page > $start ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable(\'' . $href_page . '\', \'' . $page . '\', ' . ($current_page - 1) . ')"><i class="fa fa-caret-left"></i></a></li>' : '';
					for ($i = $start; $i <= $end; $i++) {
						$table .= '<li class="c-pagination__item"><a class="c-pagination__link' . ($i == $current_page ? ' is-active' : '') . '" onclick="redirectTable(\'' . $href_page . '\', \'' . $page . '\', ' . $i . ')">' . $i . '</a></li>';
					}
					$table .= $current_page < $end ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable(\'' . $href_page . '\', \'' . $page . '\', ' . ($current_page + 1) . ')"><i class="fa fa-caret-right"></i></a></li>' : '';
					$table .= '</ul></nav>';
				}
				$table .= '</div>';
			} while (false);

			return $table;
		}

		//вывод числа в формате денег
		static function FormatMoney($value)
		{
			$money = null;
			$value = str_replace(',', '.', strval($value));

			do {
				if (empty($value))
					break;
				$value = ceil(floatval($value) * 100) / 100;

				$value = strval($value);

				if (!strrpos($value, '.'))
					$value .= '.00';

				if ((strlen($value) - strrpos($value, '.')) < 3) {
					$value .= '0';
				}

				$money = $value;

			} while (false);

			return $money;
		}

		//строковое представление денег
		static function NumberToStringMoney($number)
		{
			//округляем число до 2х знаков
			$number = ceil(floatval($number) * 100) / 100;
			//делим на рубли и копейки
			$cent = 0;
			$number = strval($number);
			str_replace($number, ",", ".");
			if (strpos(strval($number), '.') > 0) {
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
					-2 => 'две',
					-1 => 'одна',
					0 => 'ноль',
					1 => 'один',
					2 => 'два',
					3 => 'три',
					4 => 'четыре',
					5 => 'пять',
					6 => 'шесть',
					7 => 'семь',
					8 => 'восемь',
					9 => 'девять',
					10 => 'десять',
					11 => 'одиннадцать',
					12 => 'двенадцать',
					13 => 'тринадцать',
					14 => 'четырнадцать',
					15 => 'пятнадцать',
					16 => 'шестнадцать',
					17 => 'семнадцать',
					18 => 'восемнадцать',
					19 => 'девятнадцать',
					20 => 'двадцать',
					30 => 'тридцать',
					40 => 'сорок',
					50 => 'пятьдесят',
					60 => 'шестьдесят',
					70 => 'семьдесят',
					80 => 'восемьдесят',
					90 => 'девяносто',
					100 => 'сто',
					200 => 'двести',
					300 => 'триста',
					400 => 'четыреста',
					500 => 'пятьсот',
					600 => 'шестьсот',
					700 => 'семьсот',
					800 => 'восемьсот',
					900 => 'девятьсот'
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
			$number = str_pad($number, ceil(strlen($number) / 3) * 3, 0, STR_PAD_LEFT);
			$cent = str_pad($cent, ceil(strlen($cent) / 2) * 2, 0, STR_PAD_RIGHT);
			// разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
			// т.к. мы не знаем максимальный порядок числа и будем бежать снизу
			// единицы, тысячи, миллионы и т.д.
			$parts = array_reverse(str_split($number, 3));

			if (intval($number) == 0) {
				array_push($string, $dic[0][0]);
				array_push($string, $dic[1][0][2]);
			} else {
				// бежим по каждой части
				foreach ($parts as $i => $part) {

					// если часть не равна нулю, нам надо преобразовать ее в текст
					if ($part > 0) {

						// обозначаем переменную в которую будем писать составные числа для текущей части
						$digits = array();

						// если число треххзначное, запоминаем количество сотен
						if ($part > 99) {
							$digits[] = floor($part / 100) * 100;
						}

						// если последние 2 цифры не равны нулю, продолжаем искать составные числа
						if ($mod1 = $part % 100) {
							$mod2 = $part % 10;
							$flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
							if ($mod1 < 20 || !$mod2) {
								$digits[] = $flag * $mod1;
							} else {
								$digits[] = floor($mod1 / 10) * 10;
								$digits[] = $flag * $mod2;
							}
						}

						// берем последнее составное число, для плюрализации
						$last = abs(end($digits));

						// преобразуем все составные числа в слова
						foreach ($digits as $j => $digit) {
							$digits[$j] = $dic[0][$digit];
						}

						// добавляем обозначение порядка или валюту
						$digits[] = $dic[1][$i][(($last %= 100) > 4 && $last < 20) ? 2 : $dic[2][min($last % 10, 5)]];

						// объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
						array_unshift($string, join(' ', $digits));
					}
				}
			}

			//преобразуем копейки
			array_push($string, $cent);
			$cent = intval($cent);
			$cent_1 = floor($cent / 10) * 10;
			if ($cent_1 >= 20) {
				$cent_2 = $cent % $cent_1;
				if ($cent_2 > 0) {
					if ($cent_2 == 1)
						array_push($string, $dic[3][0]);
					else if ($cent_2 < 5)
						array_push($string, $dic[3][1]);
					else
						array_push($string, $dic[3][2]);
				} else
					array_push($string, $dic[3][2]);
			} else {
				if ($cent < 5) {
					if ($cent == 1)
						array_push($string, $dic[3][0]);
					else
						array_push($string, $dic[3][1]);
				} else
					array_push($string, $dic[3][2]);
			}

			// преобразуем переменную в текст и возвращаем из функции, ура!
			return join(' ', $string);
		}

		//вывод текущей даты
		static function CurrentDate($format = CONSTANTS::REPORT_DATE_FORMAT)
		{
			return date($format);
		}

		//вывод даты в формате
		static function FormatDate($date = '', $format = CONSTANTS::REPORT_DATE_FORMAT)
		{
			$dt = $date;

			do {
				if (empty($date))
					break;
				if (($timestamp = strtotime($date)) === false) {
					break;
				} else {
					$dt = date($format, $timestamp);
				}
			} while (false);

			return $dt;
		}

		//десериализация формы
		static function dSerializationForm(&$form)
		{
			$object = new classes_NotVariable();
			if (is_array($form)) {
				foreach ($form as $k => $v) {
					if (isset($v['name']) && isset($v['value'])) {
						$object->$v['name'] = $v['value'];
					} else {
						$object->$k = $v;
					}
				}
				$form = $object;
			}
		}

		//перевод строки в UTF-8
		static function toUTF8(&$value)
		{
			$to_encoding = 'UTF-8';
			if (is_array($value) || is_object($value)) {
				foreach ($value as $val) {
					self::toUTF8($val);
				}
			} else if (is_string($value)) {
				$from_encoding = mb_detect_encoding($value, array('CP1251', 'UTF-8', 'ASCII'), true);
				$from_encoding = !$from_encoding ? 'CP1251' : $from_encoding;
				if ($from_encoding != 'UTF-8') {
					$mb_convert = mb_convert_variables($to_encoding, $from_encoding, $value);
				}
			}
		}

		//перевод строки из русских символов в английский
		static function rus2translit($string)
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

		/**
		 * Поиск файла по имени во всех папках и подпапках
		 *
		 * @param string $folderName - пусть до папки
		 * @param string $fileName - искомый файл
		 */
		static function search_file($folderName, $fileName)
		{
			// открываем текущую папку
			$dir = opendir($folderName);
			// перебираем папку
			$paths = array();
			while (($file = readdir($dir)) !== false) { // перебираем пока есть файлы
				if ($file != "." && $file != "..") { // если это не папка
					if (is_file($folderName . "/" . $file)) { // если файл проверяем имя
						// если имя файла нужное, то вернем путь до него
						if ($file == $fileName) {
							$paths[] = array(
								'file' => $folderName . "/" . $file,
								'folder' => $folderName
							);
						}
					}
					// если папка, то рекурсивно вызываем search_file
					if (is_dir($folderName . "/" . $file)) $paths = array_merge($paths, self::search_file($folderName . "/" . $file, $fileName));
				}
			}
			// закрываем папку
			closedir($dir);
			return $paths;
		}

		/*
         * Создание каталога
         */
		static function createPath($path = null, $path2 = null)
		{
			$msg = null;
                        
			if(empty($path2)) {
				$path2 = $_SERVER['DOCUMENT_ROOT'] . "/pages/pg";
			}

			do {
				if (empty($path)) {
					$msg = 'Не задан путь к каталогу';
					break;
				}
				$arr_path = explode('/', $path);
				$_path = !empty($path2) ? $path2 : '';
				foreach ($arr_path as $key => $value) {
					if (empty($value))
						continue;
					$_path .= !empty($_path) ? '/' . $value : $value;

					if (!is_dir($_path)) {
						if (!mkdir($_path, 0777)) {
							$msg = "Не удалось создать каталог: " . $_path;
							break;
						}
					}
				}
			} while (false);

			return $msg;
		}

		//удаление каталога
		static function removeDirectory($dir)
		{
			if (!is_dir($dir))
				return;
			if (self::isSysFile($dir))
				return;
			if ($objs = glob($dir . "/*")) {
				foreach ($objs as $obj) {
					is_dir($obj) ? self::removeDirectory($obj) : unlink($obj);
				}
			}
			rmdir($dir);
		}

		//поиск системных файлов в директории
		static function isSysFile($path)
		{
			$result = false;

			do {
				if (empty($path))
					break;

				$path = trim($path, '/');

				$mask = array('.php', '.log', '.js', '.css', '.html', '.htaccess');

				foreach ($mask as $key => $value) {
					$files = glob($path . "/*" . $value);
					if ($files && count($files) > 0) {
						$result = true;
						break;
					}
				}

			} while (false);

			return $result;
		}

		static function deleteFile($url) {
			$msg = null;

			do {
				if(empty($url)) {
					$msg = "Не задан путь к файлу";
					break;
				}
				$url = $_SERVER['DOCUMENT_ROOT'] . "/www/" . $url;
				if(!file_exists($url)) {
					$msg = "Файл " . basename($url) . " не найден";
					break;
				}
				if(in_array(filetype($url), array('php', 'log', 'js', 'css', 'html', 'htaccess'))) {
					$msg = "Файл " . basename($url) . " является системным, его нельзя удалить";
					break;
				}
				if(!unlink($url)) {
					$msg = "Не удалось удалить файл " . basename($url);
					break;
				}
			} while(false);

			return $msg;
		}

		/**
		 * Проверка строки на JSON
		 * @param $string
		 * @return bool
		 */
		static function isJSON($string) {
			return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
		}

		/**
		 * Отрисовка таблицы через запрос
		 * $id - ид таблицы
		 * $sql - запрос, с обязательным WHERE (если он не нужен, то пишем 1=1)
		 * @return string - innerHTML таблицы
		 */
		static function viewTable2($id, $sql, $clickRow = null) {
			$_table = '';

			$_filter = new core_GetFilter($id);

			$count = intval(factorys_classes::getObject('core_Entity')->select("SELECT COUNT(*) `countRows` FROM (" . $sql . ") `tmptbl`")[0]['countRows']);

			$countPages = ceil($count / $_filter->limit);
			$_filter->page = $_filter->page > $countPages ? $countPages : $_filter->page;
			$_filter->page = empty($_filter->page) ? 1 : $_filter->page;

			/** добавляем сортировку в запрос */
			if(!empty($_filter->sort)) {
				$sql .= " ORDER BY `" . $_filter->sort . "` " . ($_filter->desc ? "DESC" : "ASC");
			} else {
				$srt = factorys_classes::getObject('core_Entity')->select($sql . " LIMIT 1")[0];
				$sql .= " ORDER BY `" . key($srt) . "` DESC";
			}

			$_start = ($_filter->page - 1) * $_filter->limit;

			/** Добавляем лимит */
			$sql .= " LIMIT " . $_start . ", " . $_filter->limit;

			$data = factorys_classes::getObject('core_Entity')->select($sql);

			// отрисовка лимита
			// массив количества записей на одной странице
			$aLimits = [10, 25, 50];

			$_table .= $_filter->getJS();
			$str_js_object = '$_TABLES_' . $_filter->_id;
			$_table .= 'Показывать <select onchange="redirectTable2(' . $str_js_object . ', \'limit\', this.value)">';
			foreach ($aLimits as $lk => $lv) {
				$_table .= '<option value="' . $lv . '"' . ($_filter->limit == $lv ? ' selected' : '') . '>' . $lv . '</option>';
			}
			$_table .= '</select> записей';

			$_table .= '<div class="c-table-responsive@desktop">';
			$_table .= '<table class="c-table" id="' . $id . '">';
			$_table .= '<thead class="c-table__head c-table__head--slim"><tr class="c-table__row">';

			// отрисовываем заголовки
			foreach ($data[0] as $k => $v) {
				$icon_sort = 'fa fa-sort';
				if($_filter->sort == $k) {
					$icon_sort = $_filter->desc ? 'fa fa-sort-desc' : 'fa fa-sort-asc';
				}
				$_table .= '<th class="c-table__cell c-table__cell--head" onclick="redirectTable2(' . $str_js_object . ', \'sort\', \'' . $k . '\')"><i class="' . $icon_sort . '"></i> ' . self::__($k) . '</th>';
			}

			$_table .= '</tr></thead>';

			$_table .= '<tbody>';
			// отрисовываем строки
			foreach ($data as $k => $v) {
				$_table .= '<tr class="c-table__row"' . (!empty($clickRow) ? ' onclick="' . $clickRow . '(' . current($v) . ', this)"' : '') . '>';

				foreach ($v as $_k => $_v) {
					$_table .= '<td class="c-table__cell">' . $_v . '</td>';
				}

				$_table .= '</tr>';
			}

			$_table .= '</tbody></table>';

			// навигация
			$_table .= '<nav class="c-pagination u-justify-center"><ul class="c-pagination__list" id="nav_' . $_filter->page . '">';
			$start = ($_filter->page - 3) < 1 ? 1 : ($_filter->page - 3);
			$end = ($_filter->page + 3) > $countPages ? $countPages : ($_filter->page + 3);
			$end = empty($end) ? 1 : $end;
			$_table .= $_filter->page > $start ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable2(' . $str_js_object . ', \'page\', ' . ($_filter->page - 1) . ')"><i class="fa fa-caret-left"></i></a></li>' : '';
			for ($i = $start; $i <= $end; $i++) {
				$_table .= '<li class="c-pagination__item"><a class="c-pagination__link' . ($i == $_filter->page ? ' is-active' : '') . '" onclick="redirectTable2(' . $str_js_object . ', \'page\', ' . $i . ')">' . $i . '</a></li>';
			}
			$_table .= $_filter->page < $end ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable2(' . $str_js_object . ', \'page\', ' . ($_filter->page + 1) . ')"><i class="fa fa-caret-right"></i></a></li>' : '';
			$_table .= '</ul></nav>';
			$_table .= '</div>';

			return $_table;
		}

	}

?>
