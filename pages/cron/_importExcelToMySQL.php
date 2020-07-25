<?php
	// Подключаем библиотеку
	require_once("PHPExcel/Classes/PHPExcel.php");
	require_once("../db.php");

	// Функция преобразования листа Excel в таблицу MySQL, с учетом объединенных строк и столбцов.
	// Значения берутся уже вычисленными. Параметры:
	//     $worksheet - лист Excel
	//     $connection - соединение с MySQL (mysqli)
	//     $table_name - имя таблицы MySQL
	//     $columns_name_line - строка с именами столбцов таблицы MySQL (0 - имена типа column + n)
	function excel2mysql($worksheet, $table_name, $columns_name_line = 0) {
		// Строка для названий столбцов таблицы MySQL
		$columns_str = "";
		// Количество столбцов на листе Excel
		$columns_count = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());

		// Перебираем столбцы листа Excel и генерируем строку с именами через запятую
		for ($column = 0; $column < $columns_count; $column++) {
		  $columns_str .= ($columns_name_line == 0 ? "column" . $column : $worksheet->getCellByColumnAndRow($column, $columns_name_line)->getCalculatedValue()) . ",";
		}
		// Обрезаем строку, убирая запятую в конце
		$columns_str = substr($columns_str, 0, -1);

		// Удаляем таблицу MySQL, если она существовала
		if (mysql_query("DROP TABLE IF EXISTS " . $table_name)) {
		  // Создаем таблицу MySQL
		  if (mysql_query("CREATE TABLE " . $table_name . " (`ID` INT(11) NOT NULL AUTO_INCREMENT," . str_replace(",", " TEXT NOT NULL,", $columns_str) . " TEXT NOT NULL, PRIMARY KEY (`ID`), UNIQUE INDEX `ID` (`ID`))")) {
			// Количество строк на листе Excel
			$rows_count = $worksheet->getHighestRow();

			// Перебираем строки листа Excel
			for ($row = $columns_name_line + 1; $row <= $rows_count; $row++) {
			  // Строка со значениями всех столбцов в строке листа Excel
			  $value_str = "";

			  // Перебираем столбцы листа Excel
			  for ($column = 0; $column < $columns_count; $column++) {
				// Строка со значением объединенных ячеек листа Excel
				$merged_value = "";
				// Ячейка листа Excel
				$cell = $worksheet->getCellByColumnAndRow($column, $row);

				// Перебираем массив объединенных ячеек листа Excel
				foreach ($worksheet->getMergeCells() as $mergedCells) {
				  // Если текущая ячейка - объединенная,
				  if ($cell->isInRange($mergedCells)) {
					// то вычисляем значение первой объединенной ячейки, и используем её в качестве значения
					// текущей ячейки
					$merged_value = $worksheet->getCell(explode(":", $mergedCells)[0])->getCalculatedValue();
					break;
				  }
				}

				// Проверяем, что ячейка не объединенная: если нет, то берем ее значение, иначе значение первой
				// объединенной ячейки
				$value_str .= "'" . str_replace("'", '"', (strlen($merged_value) == 0 ? $cell->getCalculatedValue() : $merged_value)) . "',";
			  }

			  // Обрезаем строку, убирая запятую в конце
			  $value_str = substr($value_str, 0, -1);

			  // Добавляем строку в таблицу MySQL
			  mysql_query("INSERT INTO " . $table_name . " (" . $columns_str . ") VALUES (" . $value_str . ")");
			}
		  } else {
			return false;
		  }
		} else {
		  return false;
		}
	  return true;
	}

	// Загружаем файл Excel
	$PHPExcel_file = PHPExcel_IOFactory::load("code_stat.xlsx");
	$table_name = "directoryCodeStat";

	// Преобразуем первый лист Excel в таблицу MySQL
	$PHPExcel_file->setActiveSheetIndex(0);
	echo excel2mysql($PHPExcel_file->getActiveSheet(), $table_name, 1) ? "OK\n" : "FAIL\n";
?>