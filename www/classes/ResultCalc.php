<?php
    class classes_ResultCalc extends core_Object {
        var $all_count_product = null; //количество продукции
		var $all_count = null;  //общее количество листов продукта
		var $count_list_pages = null; //количество печатных листов
		var $count_sheet = null; //количество элементов на одном печатном листе
		var $count_sheet_block = null; //количество страниц в одном блоке
		var $count_list_pages_material = null; //количество листов материала
		var $cost_material = null; //стоимость материала за  единицу
		var $name_material = null; //название материала
		var $size_material = null; //размер материала
		var $all_cost_material = null; //себестоимость материала
		var $all_cost_material_nds = null; //стоимость материала с надбавкой
		var $surcharge_client = null; //надбавка клиента
		var $surcharge_client_material = null; //надбавка клиента на материал
		var $cost_print_color = null; //стоимость операции печать
		var $widescreen = false; //флаг или широкоформатка
		var $nadb_tir = null; //надбавка на тираж
		var $print_summ_default = null; //себестоимость печати
		var $print_summ = null; //стоимость печати
		var $cost_offset = null; //стоимость работ на стороне
		var $cost_cutting = null; //стоимость резки
		var $lenght_plotter_cutting = null; //длина реза для плоттерной резки
		var $cost_plotter_cutting = null; //стоимость плоттерной резки,
		var $cost_lamination = null; //стоимость ламинации
		var $count_scoring = null; //количество биговок
		var $cost_scoring = null; //стоимость биговки
		var $count_perforation = null; //количество перфораций
		var $cost_perforation = null; //стоимость перфорации
		var $count_corner = null; //количество углов скругления
		var $cost_corner = null; //стоимость скругления
		var $count_hole = null; //количество отверстий
		var $cost_hole = null; //стоимость отверстий
		var $count_grommet = null; //количество люверсов
		var $cost_grommet = null; //стоимость люверсов
		var $count_stamp_cutting = null; //количество ударов (вырубка)
		var $cost_stamp_cutting = null; //стоимость вырубки
		var $cost_stamp_cutting_element = null; //цена штампа вырубки
		var $count_hot_stamping = null; //количество ударов (конгрев)
		var $cost_hot_stamping = null; //стоимость конгрева
		var $cost_hot_stamping_element = null; //цена штампа конгрева
		var $count_stamping = null; //количество ударов (тиснение)
		var $cost_stamping = null; //стоимость тиснения
		var $cost_stamping_element = null; //цена штампа тиснения
		var $all_summa = null; //общая сумма
		var $self_adhesive = false; //самоклейка или нет
		
		var $width = 0; //ширина изделия
		var $height = 0; // высота изделия
		var $width_print = 0; //ширина печатного листа
		var $height_print = 0; //высота печатного листа
		var $left = 0; //отступ слева/справа
		var $top = 0; //отступ сверху/снизу
		var $Layout = null; //блок раскладки
		var $LayoutArray = null; //массив раскладки
		
		public function __construct() {}
    }
?>
