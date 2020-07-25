<?php
    class classes_VariableCalc extends core_Object{
		public $id_client = null;
		public $p_cir = null;
		public $NUM = null;
		public $p_namepart = null;
		public $p_size = null;
		public $p_kolstr = null;
		public $p_eq = null;
		public $p_color = null;
		public $p_mat = null;
		public $mat_firm = null;
		public $p_sizep = null;
		public $p_new_size = null;
		public $p_cut = null;
		public $p_cut2 = null;
		public $p_size_cut = null;
		public $p_size_cut_op = null;
		public $p_size_cut_eq = null;
		public $p_size_cut2 = null;
		public $p_lam = null;
		public $p_bug = null;
		public $p_perf = null;
		public $p_ygl = null;
		public $p_otv = null;
		public $p_diam = null;
		public $p_luv = null;
		public $p_colorluv = null;
		public $p_vir = null;
		public $p_con = null;
		public $p_tis = null;
		public $p_off = null;
		public $p_prstamp = null;
		public $p_prkl = null;
		public $p_prckl = null;
		public $vin = null;
		public $max = null;
		public $pol = null;
		public $pers = null;
		public $p_stor_i = null;
		public $p_per_i = null;
		public $ResultCalc = null;
		public $combi_f = array(); //доп цвет на лицевой стороне
		public $combi_b = array(); //доп цвет на обратной стороне

        public function __construct($param = null, $num = null)
        {
			$this->ResultCalc = new classes_ResultCalc();
            if(empty($param) || empty($num))
                return;
			$this->id_client = (!empty($param->id_client)) ? $param->id_client : null;
            $this->NUM = $num;
            $p_namepart = 'p_namepart_' . $num;
            $this->p_namepart = (property_exists($param, $p_namepart) && !empty($param->$p_namepart)) ? $param->$p_namepart : '';
            $p_size = 'p_size_' . $num;
            $this->p_size = (property_exists($param, $p_size) && !empty($param->$p_size)) ? $param->$p_size : '';
            if(empty($this->p_size) && property_exists($param, 'p_size') && $param->p_size)
                $this->p_size = $param->p_size;
			$p_stor_i = 'p_stor';
			$this->p_stor_i = (property_exists($param, $p_stor_i) && !empty($param->$p_stor_i)) ? $param->$p_stor_i : 0;
            if(property_exists($param, 'p_cir') && !empty($param->p_cir)) {
                $factor = 1;
                if(property_exists($param, 'unit_prod1') && !empty($param->unit_prod1) && $param->unit_prod1 == 'тыс.шт.') {
                    $factor = 1000;
                }
                $this->p_cir = floatval($param->p_cir) * $factor;
            } else
				$this->p_cir = 0;
			$p_kolstr = 'p_kolstr_' . $num;
			$this->p_kolstr = (property_exists($param, $p_kolstr) && !empty($param->$p_kolstr) && !empty($this->p_stor_i)) ? ceil(floatval($param->$p_kolstr) / 2) : ceil(floatval($param->$p_kolstr) / 2) * $this->p_cir;
            $p_eq = 'p_eq_' . $num;
            $this->p_eq = (property_exists($param, $p_eq) && !empty($param->$p_eq)) ? $param->$p_eq : null;
            $p_color = 'p_color_' . $num;
            $this->p_color = (property_exists($param, $p_color) && !empty($param->$p_color)) ? $param->$p_color : null;
            $p_mat = 'p_mat_' . $num;
            $this->p_mat = (property_exists($param, $p_mat) && !empty($param->$p_mat)) ? $param->$p_mat : null;
            $mat_firm = 'mat_firm' . $num;
            $this->mat_firm = (property_exists($param, $mat_firm)) ? 1 : 0;
            $p_sizep = 'p_sizep_' . $num;
            $this->p_sizep = (property_exists($param, $p_sizep) && !empty($param->$p_sizep)) ? $param->$p_sizep : null;
            $p_new_size = 'p_new_size_' . $num;
            $this->p_sizep = (property_exists($param, $p_new_size) && !empty($param->$p_new_size)) ? $param->$p_new_size : $this->p_sizep;
            $p_cut = 'p_cut_' . $num;
            $this->p_cut = (property_exists($param, $p_cut)) ? 1 : 0;
            $p_cut2 = 'p_cut2_' . $num;
            $this->p_cut2 = (property_exists($param, $p_cut2)) ? 1 : 0;
            if($this->p_cut2) {
                $p_size_cut = 'p_size_cut_' . $num;
                $this->p_size_cut = (property_exists($param, $p_size_cut) && !empty($param->$p_size_cut)) ? $param->$p_size_cut : null;
            }
            $p_size_cut_op = 'p_size_cut_op_' . $num;
			$this->p_size_cut_op = (property_exists($param, $p_size_cut_op) && !empty($param->$p_size_cut_op)) ? $param->$p_size_cut_op : null;
			if(!empty($this->p_size_cut_op)) {
				$p_size_cut_eq = 'p_size_cut_eq_' . $num;
				$this->p_size_cut_eq = (property_exists($param, $p_size_cut_eq) && !empty($param->$p_size_cut_eq)) ? $param->$p_size_cut_eq : null;
				if(!empty($this->p_size_cut_eq)) {
					$p_size_cut2 = 'p_size_cut2_' . $num;
					$this->p_size_cut2 = (property_exists($param, $p_size_cut2) && !empty($param->$p_size_cut2)) ? $param->$p_size_cut2 : null;
				}
			}
            $p_lam = 'p_lam_' . $num;
            $this->p_lam = (property_exists($param, $p_lam) && !empty($param->$p_lam)) ? $param->$p_lam : null;
            if(property_exists($param, 'chkbug')) {
                $p_bug = 'p_bug_' . $num;
                $this->p_bug = (property_exists($param, $p_bug) && !empty($param->$p_bug)) ? intval($param->$p_bug) : 0;
            }
            if(property_exists($param, 'chkperf')) {
                $p_perf = 'p_perf_' . $num;
                $this->p_perf = (property_exists($param, $p_perf) && !empty($param->$p_perf)) ? intval($param->$p_perf) : 0;
            }
            if(property_exists($param, 'chkygl')) {
                $p_ygl = 'p_ygl_' . $num;
                $this->p_ygl = (property_exists($param, $p_ygl) && !empty($param->$p_ygl)) ? intval($param->$p_ygl) : 0;
            }
            if(property_exists($param, 'chkotv')) {
                $p_otv = 'p_otv_' . $num;
                $this->p_otv = (property_exists($param, $p_otv) && !empty($param->$p_otv)) ? intval($param->$p_otv) : 0;
                $p_diam = 'p_diam_' . $num;
                $this->p_diam = (property_exists($param, $p_diam) && !empty($param->$p_diam)) ? $param->$p_diam : null;
            }
            if(property_exists($param, 'chkluv')) {
                $p_luv = 'p_luv_' . $num;
                $this->p_luv = (property_exists($param, $p_luv) && !empty($param->$p_luv)) ? intval($param->$p_luv) : 0;
                $p_colorluv = 'p_colorluv_' . $num;
                $this->p_colorluv = (property_exists($param, $p_colorluv) && !empty($param->$p_colorluv)) ? $param->$p_colorluv : null;
            }
			if(property_exists($param, 'chkvir')) {
				$p_vir = 'p_vir_' . $num;
				$this->p_vir = (property_exists($param, $p_vir) && !empty($param->$p_vir)) ? $param->$p_vir : null;
				$p_prstamp = 'p_prstamp_' . $num;
				$this->p_prstamp = (property_exists($param, $p_prstamp) && !empty($param->$p_prstamp)) ? $param->$p_prstamp : null;
			}
			if(property_exists($param, 'chkcon')) {
				$p_con = 'p_con_' . $num;
				$this->p_con = (property_exists($param, $p_con) && !empty($param->$p_con)) ? $param->$p_con : null;
				$p_prkl = 'p_prkl_' . $num;
				$this->p_prkl = (property_exists($param, $p_prkl) && !empty($param->$p_prkl)) ? $param->$p_prkl : null;
			}
			if(property_exists($param, 'chktis')) {
				$p_tis = 'p_tis_' . $num;
				$this->p_tis = (property_exists($param, $p_tis) && !empty($param->$p_tis)) ? $param->$p_tis : null;
				$p_prckl = 'p_prckl_' . $num;
				$this->p_prckl = (property_exists($param, $p_prckl) && !empty($param->$p_prckl)) ? $param->$p_prckl : '';
			}
            $p_off = 'p_off_' . $num;
            $this->p_off = (property_exists($param, $p_off) && !empty($param->$p_off)) ? $param->$p_off : 0;
            $vin = 'vin' . $num;
            $this->vin = (property_exists($param, $vin) && !empty($param->$vin)) ? $param->$vin : 0;
            $max = 'max' . $num;
            $this->max = (property_exists($param, $max)) ? 1 : 0;
            $pol = 'pol' . $num;
            $this->pol = (property_exists($param, $pol)) ? 1 : 0;
            $pers = 'pers' . $num;
            $this->pers = (property_exists($param, $pers)) ? 1 : 0;
			$p_per_i = 'p_per_i';
			$this->p_per_i = (property_exists($param, $p_per_i) && !empty($param->$p_per_i)) ? $param->$p_per_i : 0;

			//массивы для доп цветов (если они есть)
			$prms = (array)$param;
			foreach ($prms as $prm => $p) {
				if(strpos($prm, $p_color . "_") !== false && strpos($prm, "_f") !== false) {
					$this->combi_f[] = $param->$prm;
				}
				if(strpos($prm, $p_color . "_") !== false && strpos($prm, "_b") !== false) {
					$this->combi_b[] = $param->$prm;
				}
			}
        }
    
		//расчет раскладки
		public function calcLayout() {
			$dLayout = null;
			$countLayout = null;
			
			do {
				if(empty($this->p_size))
					break;
				$sz = explode('*', $this->p_size);
				if(!is_array($sz))
					break;
				$this->ResultCalc->width = floatval($sz[0]);
				$this->ResultCalc->height = floatval($sz[1]);
				if(empty($this->ResultCalc->width) || empty($this->ResultCalc->height))
					break;
				
				if(empty($this->p_sizep))
					break;
				$sz = explode('*', $this->p_sizep);
				if(!is_array($sz))
					break;
				$this->ResultCalc->width_print = floatval($sz[0]);
				$this->ResultCalc->height_print = floatval($sz[1]);
				if(empty($this->ResultCalc->width_print) || empty($this->ResultCalc->height_print))
					break;
				
				$equipment = new classes_equipment();
				if(null !== ($msg = $equipment->loadById($this->p_eq))) {
					break;
				}
				
				if($this->pol) {
					$this->ResultCalc->left = 2 * floatval($equipment->ladnr);
					$this->ResultCalc->top = 2 * floatval($equipment->uandd);
				}
				if($this->p_cut2 || (!empty($this->p_size_cut_op) && !empty($this->p_size_cut_eq))) {
					$this->ResultCalc->left = 50;
					$this->ResultCalc->top = 70;
				}
				
				if($this->pers && $this->p_per_i == 7) {
					$w = ceil(2 * floatval($this->ResultCalc->width));
					$h = ceil(2 * floatval($this->ResultCalc->height));
					if($this->p_stor_i == 1) {
						if($w > $h)
							$this->ResultCalc->width = $w;
						else
							$this->ResultCalc->height = $h;
					}
					else if($this->p_stor_i == 2) {
						if($w < $h)
							$this->ResultCalc->width = $w;
						else
							$this->ResultCalc->height = $h;
					}
				}
				
				$countLayout = $this->calcCountLayout();
				$_p_kolstr = !empty($this->p_kolstr) ? $this->p_kolstr : $this->p_cir;
				$countLayout['count_pages'] = $countLayout['count'] > 0 ? ceil($_p_kolstr / $countLayout['count']) : 0;
				
				//собираем блок раскладки
				$koeff = 1;
				if($countLayout['width_page'] >= $countLayout['height_page'])
					$koeff = 120 / $countLayout['width_page'];
				else
					$koeff = 120 / $countLayout['height_page'];
				$dLayout = '<div id="block_table_'.$this->NUM.'" class="block_table" style="width:'.($koeff*$countLayout['width_page']).'px;height:'.($koeff*$countLayout['height_page']).'px">';
				$d_left = ($this->ResultCalc->left/2)/* + $this->vin*/;
				$d_top = ($this->ResultCalc->top/2)/* + $this->vin*/;
				for($i = 0; $i < $countLayout['count']; $i++) {
					if($d_left > (($this->ResultCalc->left/2) + $this->vin)) {
						//если вышли за пределы страницы, то меняем отступы на начальные
						if(($d_left + 2 * $this->vin + ($this->ResultCalc->left/2) + $countLayout['width_product']) > $countLayout['width_page']) {
							$d_left = ($this->ResultCalc->left/2)/* + $this->vin*/;
							$d_top += $countLayout['height_product'] + 2 * $this->vin;
						}
					}
					$_style = 'width:'.($koeff*$countLayout['width_product']).'px;';
					$_style .= 'height:'.($koeff*$countLayout['height_product']).'px;';
					$_style .= 'left:'.($koeff*$d_left).'px;';
					$_style .= 'top:'.($koeff*$d_top).'px;';
					$dLayout .= '<div class="bl" style="'.$_style.'"></div>';
					$d_left += $countLayout['width_product'] + 2 * $this->vin;
				}
				//для максимального заполнения
				if($this->max) {
					$tmp_width = 0;
					$tmp_height = 0;
					$_d_left = 0;
					$_d_top = 0;
					//определяем или можно впихнуть ещё элементы
					//снизу
					if(($countLayout['width_product'] + 2 * $this->vin) <= $countLayout['height_delta']) {
						$_d_left = ($this->ResultCalc->left/2) + $this->vin;
						$_d_top = $d_top + $countLayout['height_product'] + $this->vin;
						$tmp_width = $countLayout['width_page'] - $this->ResultCalc->left;
						$tmp_height = $countLayout['height_delta'];
					}
					//справа
					else if(($countLayout['height_product'] + 2 * $this->vin) <= $countLayout['width_delta']) {
						$_d_left = $d_left;
						$_d_top = ($this->top/2) + $this->vin;
						$tmp_width = $countLayout['width_delta'];
						$tmp_height = $countLayout['height_page'] - $this->ResultCalc->top;
					}
					//если есть остаток
					if($tmp_width > 0 && $tmp_height > 0) {
						//для этого куска 2 варианта раскладки
						$_d1 = array(
							'count' => floor($tmp_width/($countLayout['width_product'] + 2*$this->vin)) * floor($tmp_height/($countLayout['height_product'] + 2*$this->vin)),
							'width_product' => $countLayout['width_product'],
							'height_product' => $countLayout['height_product'],
						);
						$_d2 = array(
							'count' => floor($tmp_width/($countLayout['height_product'] + 2*$this->vin)) * floor($tmp_height/($countLayout['width_product'] + 2*$this->vin)),
							'width_product' => $countLayout['height_product'],
							'height_product' => $countLayout['width_product'],
						);
						$_d = $_d1;
						if($_d['count'] < $_d2['count'])
							$_d = $_d2;
						if($_d['count'] > 0) {
							//пересчитываем количество на странице и в тираже
							$countLayout['count'] += $_d['count'];
							$countLayout['count_pages'] = ceil($_p_kolstr / $countLayout['count']);
							//рисуем раскладку
							$d_left = $_d_left;
							$d_top = $_d_top;
							for($i = 0; $i < $_d['count']; $i++) {
								//если вышли за пределы страницы, то меняем отступы на начальные
								if(($d_left + 2 * $this->vin + ($this->ResultCalc->left/2) + $_d['width_product']) > $countLayout['width_page']) {
									$d_left = $_d_left;
									$d_top += $_d['height_product'] + 2 * $this->vin;
								}
								$style = 'width:'.($_d['width_product']*$koeff).'px;';
								$style .= 'height:'.($_d['height_product']*$koeff).'px;';
								$style .= 'left:'.($d_left*$koeff).'px;';
								$style .= 'top:'.($d_top*$koeff).'px;';
								$dLayout .= '<div class="bl" style="'.$style.'"></div>';
								
								$d_left += $_d['width_product'] + 2 * $this->vin;
							}
						}
					}
				}
				$dLayout .= '</div>';
				
				$this->ResultCalc->Layout = $dLayout;
				$this->ResultCalc->LayoutArray = $countLayout;
				$this->ResultCalc->count_sheet = $countLayout['count'];
				
			} while(false);		
		}

		//расчет вариантов раскладки
		protected function calcCountLayout() {
			$d1 = array(
				'count' => floor(($this->ResultCalc->width_print - $this->ResultCalc->left) / ($this->ResultCalc->width + 2 * $this->vin)) * floor(($this->ResultCalc->height_print - $this->ResultCalc->top) / ($this->ResultCalc->height + 2 * $this->vin)),
				'width_product' => $this->ResultCalc->width,
				'height_product' => $this->ResultCalc->height,
				'width_page' => $this->ResultCalc->width_print,
				'height_page' => $this->ResultCalc->height_print,
			);
			$d2 = array(
				'count' => floor(($this->ResultCalc->width_print - $this->ResultCalc->left) / ($this->ResultCalc->height + 2 * $this->vin)) * floor(($this->ResultCalc->height_print - $this->ResultCalc->top) / ($this->ResultCalc->width + 2 * $this->vin)),
				'width_product' => $this->ResultCalc->height,
				'height_product' => $this->ResultCalc->width,
				'width_page' => $this->ResultCalc->width_print,
				'height_page' => $this->ResultCalc->height_print,
			);
			$d3 = array(
				'count' => floor(($this->ResultCalc->height_print - $this->ResultCalc->left) / ($this->ResultCalc->width + 2 * $this->vin)) * floor(($this->ResultCalc->width_print - $this->ResultCalc->top) / ($this->ResultCalc->height + 2 * $this->vin)),
				'width_product' => $this->ResultCalc->width,
				'height_product' => $this->ResultCalc->height,
				'width_page' => $this->ResultCalc->height_print,
				'height_page' => $this->ResultCalc->width_print,
			);
			$d4 = array(
				'count' => floor(($this->ResultCalc->height_print - $this->ResultCalc->left) / ($this->ResultCalc->height + 2 * $this->vin)) * floor(($this->ResultCalc->width_print - $this->ResultCalc->top) / ($this->ResultCalc->width + 2 * $this->vin)),
				'width_product' => $this->ResultCalc->height,
				'height_product' => $this->ResultCalc->width,
				'width_page' => $this->ResultCalc->height_print,
				'height_page' => $this->ResultCalc->width_print,
			);
			
			$d = $d1;
			if($d['count'] < $d2['count'])
				$d = $d2;
			if($d['count'] < $d3['count'])
				$d = $d3;
			if($d['count'] < $d4['count'])
				$d = $d4;
			
			$d['width_delta'] = ($d['width_page'] - $this->ResultCalc->left) - floor(($d['width_page'] - $this->ResultCalc->left) / ($d['width_product'] + 2 * $this->vin)) * ($d['width_product'] + 2 * $this->vin);
			$d['height_delta'] = ($d['height_page'] - $this->ResultCalc->top) - floor(($d['height_page'] - $this->ResultCalc->top) / ($d['height_product'] + 2 * $this->vin)) * ($d['height_product'] + 2 * $this->vin);
			
			return $d;
		}

		//полный расчет
		public function calcElement() {
			$this->calcLayout();
        	$this->calcMaterial();
        	$this->calcPrint();
        	$this->ResultCalc->cost_offset = floatval($this->p_off);
        	$this->calcCut();
        	$this->calcPlotterCut2();
        	$this->calcLamination();
        	$this->calcBug();
        	$this->calcPerf();
        	$this->calcYgl();
        	$this->calcOtv();
        	$this->calcLuv();
        	$this->calcVir();
        	$this->calcCon();
        	$this->calcTis();
        	$this->calcAllSum();
		}

		//расчет материалов
		protected function calcMaterial() {
        	$var = array(
        		'vynos' => 0,
        		'left' => 0,
        		'top' => 0,
        		'count_product' => $this->ResultCalc->LayoutArray['count_pages'],
        		'size' => $this->p_sizep,
        		'material' => $this->p_mat,
			);
        	$material = new classes_MaterialInnerSize($var);
        	$res_material = $material->SelectMaterial();
        	$this->ResultCalc->cost_material = $this->mat_firm != 1 ? $res_material->M_PRICE : 0;
        	$this->ResultCalc->name_material = $res_material->M_NAME;
        	$this->ResultCalc->size_material = $res_material->M_SIZE;
        	if($res_material->count_sheet['count'] > 0) {
				$this->ResultCalc->cost_material = $this->ResultCalc->cost_material / $res_material->count_sheet['count'];
				$this->ResultCalc->count_list_pages_material = $this->ResultCalc->LayoutArray['count_pages'];
			}
        	else {
				$this->ResultCalc->count_list_pages_material = 0;
			}
        	$this->ResultCalc->all_cost_material = $this->ResultCalc->count_list_pages_material * $this->ResultCalc->cost_material;
        	$this->ResultCalc->surcharge_client = $this->getNadbavkaClient();
        	$this->ResultCalc->surcharge_client_material = $this->getNadbavkaClient();
        	$this->ResultCalc->all_cost_material_nds = $this->ResultCalc->all_cost_material * $this->ResultCalc->surcharge_client_material;
        	$this->ResultCalc->count_sheet_block = $this->p_kolstr;
        	$this->ResultCalc->all_count = !empty($this->p_kolstr) ? ($this->p_kolstr * $this->p_cir) : $this->p_cir;
        	$this->ResultCalc->all_count_product = $this->p_cir;
        	$this->ResultCalc->widescreen = $res_material->widescreen;
        	$klMat = new classes_klMat();
        	if(null === ($msg = $klMat->loadById($res_material->id_tree))) {
        		$this->ResultCalc->self_adhesive = $klMat->getSelfAdhesive();
			}
		}

		//расчет надбавки клиента
		protected function getNadbavkaClient() {
        	$val = 1;

        	do {
				$client = new classes_clients();
				$client->loadById($this->id_client);
				switch ($client->NADBAVKA) {
					case 2:
						$val = 1.1;
						break;
					case 3:
						$val = 1.3;
						break;
					case 5:
						$val = 1.2;
						break;
					default:
						$val = 1.4;
						break;
				}
			} while(false);

        	return $val;
		}

		//расчет печати
		protected function calcPrint() {
			do {
				$this->ResultCalc->count_list_pages = $this->ResultCalc->LayoutArray['count_pages'];
				//параметры принтера
				$equipment = new classes_equipment();
				if (null !== ($msg = $equipment->loadById($this->p_eq))) {
					break;
				}
				$nadb_max = floatval($equipment->nadb_max) / 100;
				$nadb_min = floatval($equipment->nadb_min) / 100;
				$total_max = floatval($equipment->total_max);
				$total_min = floatval($equipment->total_min);

				//стоимость печати
				$f_class = factorys_FactoryEquipment::bind($this->p_eq);
				if(empty($f_class)) {
					$operation = new classes_operations();
					if(null !== ($msg = $operation->loadById($this->p_color))) {
						break;
					}
					$this->ResultCalc->cost_print_color = floatval($operation->OPERATION_PRICE);
				} else {
					$this->ResultCalc->cost_print_color = floatval($f_class->getOperationPrice(get_object_vars($this)));
				}

				//для широкоформатки
				if($this->ResultCalc->widescreen) {
					$sqr = floatval($this->ResultCalc->width) * floatval($this->ResultCalc->height) / 1000000;
					$this->ResultCalc->cost_print_color = $this->ResultCalc->cost_print_color * $sqr * $this->ResultCalc->all_count;
				}

				if($total_max < $this->ResultCalc->count_list_pages)
					$total_max = $this->ResultCalc->count_list_pages;

				//надбавка за тираж
				$b_ = ($total_min - $total_max) != 0 ? ($nadb_max - $nadb_min) / ($total_min - $total_max) : 0;
				$a_ = $nadb_max - ($b_ * $total_min);
				$this->ResultCalc->nadb_tir = $a_ + $b_ * intval($this->ResultCalc->count_list_pages);

				//стоимость печати - результат
				$this->ResultCalc->print_summ_default = $this->ResultCalc->count_list_pages * $this->ResultCalc->cost_print_color;
				$this->ResultCalc->print_summ = $this->ResultCalc->count_list_pages * $this->ResultCalc->cost_print_color * $this->ResultCalc->surcharge_client * $this->ResultCalc->nadb_tir;

			} while(false);
		}

		//расчет резки
		protected function calcCut() {
        	do {
				if(!$this->p_cut)
					break;
				if($this->ResultCalc->widescreen) {
					//из размера материала вытаскиваем погонные метры
					$m_p = $this->ResultCalc->height;
					if($m_p) {
						$m_p = floatval($m_p) / 1000;
						//0.15$ за 1м/п
						$this->ResultCalc->cost_cutting = 0.15 * $m_p;
					}
				} else {
					//берем 10 процентов от суммы печати и материалов
					$this->ResultCalc->cost_cutting = 0.1 * ($this->ResultCalc->all_cost_material + $this->ResultCalc->print_summ_default);
				}
			} while(false);
		}

		//расчет плоттерной резки
		protected function calcPlotterCut() {
			do {
				if(!$this->p_cut2)
					break;
				$this->ResultCalc->lenght_plotter_cutting = $this->p_size_cut * $this->p_cir / 1000;
				if($this->ResultCalc->self_adhesive) {
					$operation = new classes_operations();
					if(null !== ($msg = $operation->loadById(93)))
						break;
					$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * floatval($operation->OPERATION_PRICE);
				} else {
					if ($this->ResultCalc->lenght_plotter_cutting > 0 && $this->ResultCalc->lenght_plotter_cutting < 10) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.3;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 10 && $this->ResultCalc->lenght_plotter_cutting < 20) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.25;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 20 && $this->ResultCalc->lenght_plotter_cutting < 50) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.2;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 50 && $this->ResultCalc->lenght_plotter_cutting < 100) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.1;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 100 && $this->ResultCalc->lenght_plotter_cutting < 500) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.08;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 500 && $this->ResultCalc->lenght_plotter_cutting < 1000) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.07;
					} else if ($this->ResultCalc->lenght_plotter_cutting >= 1000) {
						$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * 0.06;
					}
				}
			} while(false);
		}

		//расчет плоттерной резки
		protected function calcPlotterCut2() {
			do {
				if(empty($this->p_size_cut_op) || empty($this->p_size_cut_eq)) {
					$this->calcPlotterCut();
					break;
				}
				/** определяем оборудование */
				$eq = new classes_equipment();
				if(null !== ($msg = $eq->loadById($this->p_size_cut_eq))) {
					break;
				}
				$arr_oper_by_eq = explode(',', $eq->oper);
				/** ищем первую операцию */
				$operation = new classes_operations();
				$search_oper = false;
				$opers = explode(',', $this->p_size_cut_op);
				foreach ($opers as $k => $v) {
					if(in_array($v, $arr_oper_by_eq)) {
						$search_oper = true;
						if(null !== ($msg = $operation->loadById($v))) {
							break(2);
						}
						break;
					}
				}
				if(!$search_oper)
					break;
				$this->ResultCalc->lenght_plotter_cutting = $this->p_size_cut2 * $this->p_cir / 1000;
				$this->ResultCalc->cost_plotter_cutting = $this->ResultCalc->lenght_plotter_cutting * floatval($operation->OPERATION_PRICE);
			} while(false);
		}

		//расчет ламинации
		protected function calcLamination() {
        	do {
				if(empty($this->p_lam))
					break;
				$lamination = new classes_directorylaminat();
				if(null !== ($msg = $lamination->loadByUnique('value', $this->p_lam)))
					break;
				$client = new classes_clients();
				$client->loadById($this->id_client);
				switch ($client->NADBAVKA) {
					case 2:
						$this->ResultCalc->cost_lamination = $this->ResultCalc->count_list_pages * $lamination->nadb_2;
						break;
					case 3:
						$this->ResultCalc->cost_lamination = $this->ResultCalc->count_list_pages * $lamination->nadb_3;
						break;
					case 5:
						$this->ResultCalc->cost_lamination = $this->ResultCalc->count_list_pages * $lamination->nadb_5;
						break;
					default:
						$this->ResultCalc->cost_lamination = $this->ResultCalc->count_list_pages * $lamination->nadb_default;
						break;
				}
			} while(false);
		}

		//расчет биговки
		protected function calcBug() {
        	do {
				if(empty($this->p_bug))
					break;
				$this->ResultCalc->count_scoring = floatval($this->p_bug) * $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet;

				if ($this->ResultCalc->count_scoring > 0 && $this->ResultCalc->count_scoring < 50) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.06;
				} else if ($this->ResultCalc->count_scoring >= 50 && $this->ResultCalc->count_scoring < 100) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.05;
				} else if ($this->ResultCalc->count_scoring >= 100 && $this->ResultCalc->count_scoring < 200) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.04;
				} else if ($this->ResultCalc->count_scoring >= 200 && $this->ResultCalc->count_scoring < 300) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.035;
				} else if ($this->ResultCalc->count_scoring >= 300 && $this->ResultCalc->count_scoring < 500) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.03;
				} else if ($this->ResultCalc->count_scoring >= 500 && $this->ResultCalc->count_scoring < 1000) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.025;
				} else if ($this->ResultCalc->count_scoring >= 1000 && $this->ResultCalc->count_scoring < 2000) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.02;
				} else if ($this->ResultCalc->count_scoring >= 2000 && $this->ResultCalc->count_scoring < 3000) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.01;
				} else if ($this->ResultCalc->count_scoring >= 3000 && $this->ResultCalc->count_scoring < 5000) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.009;
				} else if ($this->ResultCalc->count_scoring >= 5000) {
					$this->ResultCalc->cost_scoring = $this->ResultCalc->count_scoring * 0.008;
				}
			} while(false);
		}

		//расчет перфорации
		protected function calcPerf() {
        	do {
        		if(empty($this->p_perf))
        			break;

				$this->ResultCalc->count_perforation = floatval($this->p_perf) * $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet;

				if ($this->ResultCalc->count_perforation > 0 && $this->ResultCalc->count_perforation < 50) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.065;
				} else if ($this->ResultCalc->count_perforation >= 50 && $this->ResultCalc->count_perforation < 100) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.055;
				} else if ($this->ResultCalc->count_perforation >= 100 && $this->ResultCalc->count_perforation < 200) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.045;
				} else if ($this->ResultCalc->count_perforation >= 200 && $this->ResultCalc->count_perforation < 300) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.04;
				} else if ($this->ResultCalc->count_perforation >= 300 && $this->ResultCalc->count_perforation < 500) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.035;
				} else if ($this->ResultCalc->count_perforation >= 500 && $this->ResultCalc->count_perforation < 1000) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.03;
				} else if ($this->ResultCalc->count_perforation >= 1000 && $this->ResultCalc->count_perforation < 2000) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.025;
				} else if ($this->ResultCalc->count_perforation >= 2000 && $this->ResultCalc->count_perforation < 3000) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.015;
				} else if ($this->ResultCalc->count_perforation >= 3000 && $this->ResultCalc->count_perforation < 5000) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.014;
				} else if ($this->ResultCalc->count_perforation >= 5000) {
					$this->ResultCalc->cost_perforation = $this->ResultCalc->count_perforation * 0.013;
				}
			} while(false);
		}

		//расчет скругления углов
		public function calcYgl() {
			do {
				if(empty($this->p_ygl))
					break;
				$this->ResultCalc->count_corner = floatval($this->p_ygl) * $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet;

				if ($this->ResultCalc->count_corner > 0 && $this->ResultCalc->count_corner < 50) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.005;
				} else if ($this->ResultCalc->count_corner >= 50 && $this->ResultCalc->count_corner < 100) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.004;
				} else if ($this->ResultCalc->count_corner >= 100 && $this->ResultCalc->count_corner < 200) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0035;
				} else if ($this->ResultCalc->count_corner >= 200 && $this->ResultCalc->count_corner < 300) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.003;
				} else if ($this->ResultCalc->count_corner >= 300 && $this->ResultCalc->count_corner < 500) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0025;
				} else if ($this->ResultCalc->count_corner >= 500 && $this->ResultCalc->count_corner < 1000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0023;
				} else if ($this->ResultCalc->count_corner >= 1000 && $this->ResultCalc->count_corner < 2000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0022;
				} else if ($this->ResultCalc->count_corner >= 2000 && $this->ResultCalc->count_corner < 3000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0021;
				} else if ($this->ResultCalc->count_corner >= 3000 && $this->ResultCalc->count_corner < 5000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.002;
				} else if ($this->ResultCalc->count_corner >= 5000 && $this->ResultCalc->count_corner < 10000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0019;
				} else if ($this->ResultCalc->count_corner >= 10000 && $this->ResultCalc->count_corner < 20000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0018;
				} else if ($this->ResultCalc->count_corner >= 20000 && $this->ResultCalc->count_corner < 30000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0017;
				} else if ($this->ResultCalc->count_corner >= 30000 && $this->ResultCalc->count_corner < 50000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0016;
				} else if ($this->ResultCalc->count_corner >= 50000) {
					$this->ResultCalc->cost_corner = $this->ResultCalc->count_corner * 0.0015;
				}
			} while(false);
		}

		//расчет отверстий
		public function calcOtv() {
			do {
				if(empty($this->p_otv))
					break;
				$this->ResultCalc->count_hole = floatval($this->p_otv) * $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet;

				if ($this->ResultCalc->count_hole > 0 && $this->ResultCalc->count_hole < 50) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.02;
				} else if ($this->ResultCalc->count_hole >= 50 && $this->ResultCalc->count_hole < 100) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.01;
				} else if ($this->ResultCalc->count_hole >= 100 && $this->ResultCalc->count_hole < 200) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.009;
				} else if ($this->ResultCalc->count_hole >= 200 && $this->ResultCalc->count_hole < 300) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.008;
				} else if ($this->ResultCalc->count_hole >= 300 && $this->ResultCalc->count_hole < 500) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.007;
				} else if ($this->ResultCalc->count_hole >= 500 && $this->ResultCalc->count_hole < 1000) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.006;
				} else if ($this->ResultCalc->count_hole >= 1000 && $this->ResultCalc->count_hole < 2000) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.005;
				} else if ($this->ResultCalc->count_hole >= 2000 && $this->ResultCalc->count_hole < 3000) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.004;
				} else if ($this->ResultCalc->count_hole >= 3000) {
					$this->ResultCalc->cost_hole = $this->ResultCalc->count_hole * 0.003;
				}
			} while(false);
		}

		//расчет люверса
		public function calcLuv() {
			do {
				if(empty($this->p_luv))
					break;

				$this->ResultCalc->count_grommet = floatval($this->p_luv) * $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet;

				if ($this->ResultCalc->count_grommet > 0 && $this->ResultCalc->count_grommet < 50) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.15;
				} else if ($this->ResultCalc->count_grommet >= 50 && $this->ResultCalc->count_grommet < 100) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.13;
				} else if ($this->ResultCalc->count_grommet >= 100 && $this->ResultCalc->count_grommet < 200) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.12;
				} else if ($this->ResultCalc->count_grommet >= 200 && $this->ResultCalc->count_grommet < 300) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.11;
				} else if ($this->ResultCalc->count_grommet >= 300 && $this->ResultCalc->count_grommet < 500) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.1;
				} else if ($this->ResultCalc->count_grommet >= 500 && $this->ResultCalc->count_grommet < 1000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.09;
				} else if ($this->ResultCalc->count_grommet >= 1000 && $this->ResultCalc->count_grommet < 2000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.085;
				} else if ($this->ResultCalc->count_grommet >= 2000 && $this->ResultCalc->count_grommet < 3000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.08;
				} else if ($this->ResultCalc->count_grommet >= 3000 && $this->ResultCalc->count_grommet < 5000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.075;
				} else if ($this->ResultCalc->count_grommet >= 5000 && $this->ResultCalc->count_grommet < 10000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.07;
				} else if ($this->ResultCalc->count_grommet >= 10000 && $this->ResultCalc->count_grommet < 20000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.065;
				} else if ($this->ResultCalc->count_grommet >= 20000 && $this->ResultCalc->count_grommet < 30000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.06;
				} else if ($this->ResultCalc->count_grommet >= 30000 && $this->ResultCalc->count_grommet < 50000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.055;
				} else if ($this->ResultCalc->count_grommet >= 50000) {
					$this->ResultCalc->cost_grommet = $this->ResultCalc->count_grommet * 0.05;
				}
			} while(false);
		}

		//расчет вырубки
		public function calcVir() {
			do {
				if(empty($this->p_vir))
					break;

				$this->ResultCalc->count_stamp_cutting = $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet / floatval($this->p_vir);
				$this->ResultCalc->count_stamp_cutting = ceil($this->ResultCalc->count_stamp_cutting);

				if ($this->ResultCalc->count_stamp_cutting > 0 && $this->ResultCalc->count_stamp_cutting < 50) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.6;
				} else if ($this->ResultCalc->count_stamp_cutting >= 50 && $this->ResultCalc->count_stamp_cutting < 100) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.3;
				} else if ($this->ResultCalc->count_stamp_cutting >= 100 && $this->ResultCalc->count_stamp_cutting < 200) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.25;
				} else if ($this->ResultCalc->count_stamp_cutting >= 200 && $this->ResultCalc->count_stamp_cutting < 300) {
					$this->ResultCalc->cost_stamp_cutting =$this->ResultCalc->count_stamp_cutting * 0.2;
				} else if ($this->ResultCalc->count_stamp_cutting >= 300 && $this->ResultCalc->count_stamp_cutting < 500) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.15;
				} else if ($this->ResultCalc->count_stamp_cutting >= 500 && $this->ResultCalc->count_stamp_cutting < 1000) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.1;
				} else if ($this->ResultCalc->count_stamp_cutting >= 1000 && $this->ResultCalc->count_stamp_cutting < 2000) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.08;
				} else if ($this->ResultCalc->count_stamp_cutting >= 2000 && $this->ResultCalc->count_stamp_cutting < 3000) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.06;
				} else if ($this->ResultCalc->count_stamp_cutting >= 3000 && $this->ResultCalc->count_stamp_cutting < 5000) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.05;
				} else if ($this->ResultCalc->count_stamp_cutting >= 5000) {
					$this->ResultCalc->cost_stamp_cutting = $this->ResultCalc->count_stamp_cutting * 0.04;
				}

				if(empty($this->p_prstamp))
					break;
				$this->ResultCalc->cost_stamp_cutting_element = floatval($this->p_prstamp);

			} while(false);
		}

		//расчет конгрева
		public function calcCon() {
			do {
				if(empty($this->p_con))
					break;

				$this->ResultCalc->count_hot_stamping = $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet / floatval($this->p_con);
				$this->ResultCalc->count_hot_stamping = ceil($this->ResultCalc->count_hot_stamping);

				if ($this->ResultCalc->count_hot_stamping > 0 && $this->ResultCalc->count_hot_stamping < 50) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.8;
				} else if ($this->ResultCalc->count_hot_stamping >= 50 && $this->ResultCalc->count_hot_stamping < 100) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.45;
				} else if ($this->ResultCalc->count_hot_stamping >= 100 && $this->ResultCalc->count_hot_stamping < 200) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.27;
				} else if ($this->ResultCalc->count_hot_stamping >= 200 && $this->ResultCalc->count_hot_stamping < 300) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.23;
				} else if ($this->ResultCalc->count_hot_stamping >= 300 && $this->ResultCalc->count_hot_stamping < 500) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.16;
				} else if ($this->ResultCalc->count_hot_stamping >= 500 && $this->ResultCalc->count_hot_stamping < 1000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.09;
				} else if ($this->ResultCalc->count_hot_stamping >= 1000 && $this->ResultCalc->count_hot_stamping < 2000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.08;
				} else if ($this->ResultCalc->count_hot_stamping >= 2000 && $this->ResultCalc->count_hot_stamping < 3000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.07;
				} else if ($this->ResultCalc->count_hot_stamping >= 3000 && $this->ResultCalc->count_hot_stamping < 5000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.06;
				} else if ($this->ResultCalc->count_hot_stamping >= 5000 && $this->ResultCalc->count_hot_stamping < 10000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.05;
				} else if ($this->ResultCalc->count_hot_stamping >= 10000) {
					$this->ResultCalc->cost_hot_stamping = $this->ResultCalc->count_hot_stamping * 0.04;
				}

				if(empty($this->p_prkl))
					break;
				$this->ResultCalc->cost_hot_stamping_element = floatval($this->p_prkl);

			} while(false);
		}

		//расчет тиснения
		public function calcTis() {
			do {
				if(empty($this->p_tis))
					break;

				$this->ResultCalc->count_stamping = $this->ResultCalc->count_list_pages * $this->ResultCalc->count_sheet / floatval($this->p_tis);

				if ($this->ResultCalc->count_stamping > 0 && $this->ResultCalc->count_stamping < 50) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.9;
				} else if ($this->ResultCalc->count_stamping >= 50 && $this->ResultCalc->count_stamping < 100) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.5;
				} else if ($this->ResultCalc->count_stamping >= 100 && $this->ResultCalc->count_stamping < 200) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.3;
				} else if ($this->ResultCalc->count_stamping >= 200 && $this->ResultCalc->count_stamping < 300) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.25;
				} else if ($this->ResultCalc->count_stamping >= 300 && $this->ResultCalc->count_stamping < 500) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.17;
				} else if ($this->ResultCalc->count_stamping >= 500 && $this->ResultCalc->count_stamping < 1000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.1;
				} else if ($this->ResultCalc->count_stamping >= 1000 && $this->ResultCalc->count_stamping < 2000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.09;
				} else if ($this->ResultCalc->count_stamping >= 2000 && $this->ResultCalc->count_stamping < 3000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.08;
				} else if ($this->ResultCalc->count_stamping >= 3000 && $this->ResultCalc->count_stamping < 5000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.07;
				} else if ($this->ResultCalc->count_stamping >= 5000 && $this->ResultCalc->count_stamping < 10000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.06;
				} else if ($this->ResultCalc->count_stamping >= 10000 && $this->ResultCalc->count_stamping < 20000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.05;
				} else if ($this->ResultCalc->count_stamping >= 20000) {
					$this->ResultCalc->cost_stamping = $this->ResultCalc->count_stamping * 0.04;
				}

				if(empty($this->p_prckl))
					break;
				$this->ResultCalc->cost_stamping_element = floatval($this->p_prckl);

			} while(false);
		}

		//расчет общей суммы по элементу
		public function calcAllSum() {
			$this->ResultCalc->all_summa = floatval($this->ResultCalc->all_cost_material_nds);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->print_summ);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_offset);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_cutting);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_plotter_cutting);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_lamination);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_scoring);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_perforation);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_corner);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_hole);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_grommet);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_stamp_cutting);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_stamp_cutting_element);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_hot_stamping);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_hot_stamping_element);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_stamping);
			$this->ResultCalc->all_summa += floatval($this->ResultCalc->cost_stamping_element);
		}
	}
?>
