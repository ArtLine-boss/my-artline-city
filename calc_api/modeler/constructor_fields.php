<?
	//ОПРЕДЕЛЕНИЕ КАКОЙ КОНСТРУКТОР ВЫЗЫВАТЬ
	function init_constructor_fields($data) {
		$return = "";
		
		switch($data['type_data']) {
			case 'text':
				$return = fld_text($data);
				break;
			case 'number':
				$return = fld_number($data);
				break;
			case 'checkbox':
				$return = fld_checkbox($data);
				break;
			case 'select':
				$return = fld_select($data);
				break;
			case 'email':
				$return = fld_text($data);
				break;
			case 'tel':
				$return = fld_text($data);
				break;
			case 'radio_img':
				$return = fld_radio_img($data);
				break;
			case 'textarea':
				$return = fld_textarea($data);
				break;
		}
		
		return $return;
	}
	
	//тектовые поля (text, email,tel)
	function fld_text($data) {
		$return = "";
		$label = $data['name'];
		$ch = "";
		$id_name = $data['id_name'];
		if(!empty($data['flag_checked']) && $data['flag_checked'] == 1) {
			$ch = ' data-checked="true"';
		}
		$str_val = "";
		if(!empty($data['value_default'])) {
			$val = json_decode($data['value_default']);
			if(!empty($val)) {
				if(!empty($val->placeholder)) {
					$str_val = ' placeholder="'.$val->placeholder.'"';
				}
				if(!empty($val->value)) {
					$str_val .= ' value="'.$val->value.'"';
				}
			}
		}
		
		$display_none = "";
		if(!empty($data['display_none'])) {
			$display_none = ' style="display:none;"';
		}
		
		$return = '<div'.$display_none.'>
						<label class="c-label">'.$label.'</label>
						<div class="row">
							<input class="c-input" id="'.$id_name.'" name="'.$id_name.'" type="'.$data['type_data'].'"'.$ch.$str_val.'>
						</div>
					</div>';
		return $return;
	}
	
	//числовое поле
	function fld_number($data) {
		$return = "";
		$label = $data['name'];
		$ch = "";
		$id_name = $data['id_name'];
		if(!empty($data['flag_checked']) && $data['flag_checked'] == 1) {
			$ch = ' data-checked="true"';
		}
		$str_val = "";
		if(!empty($data['value_default'])) {
			$val = json_decode($data['value_default']);
			if(!empty($val)) {
				if(!empty($val->min_value)) {
					$str_val .= ' min="'.$val->min_value.'"';
				}
				if(!empty($val->max_value)) {
					$str_val .= ' max="'.$val->max_value.'"';
				}
				if(!empty($val->value)) {
					$str_val .= ' value="'.$val->value.'"';
				}
				if(!empty($val->step)) {
					$str_val .= ' step="'.$val->step.'"';
				}
			}
		}
		
		$display_none = "";
		if(!empty($data['display_none'])) {
			$display_none = ' style="display:none;"';
		}
		
		$return = '<div'.$display_none.'>
						<label class="c-label">'.$label.'</label>
						<div class="row">
							<input class="c-input" id="'.$id_name.'" name="'.$id_name.'" type="number"'.$ch.$str_val.'>
						</div>
					</div>';
		return $return;
	}
	
	//чекбокс
	function fld_checkbox($data) {
		$return = "";
		$label = $data['name'];
		$id_name = $data['id_name'];
		$str_val = ' value="false"';
		$script = "";
		if(!empty($data['value_default'])) {
			$val = json_decode($data['value_default']);
			if(!empty($val->value)) {
				$str_val = ' value="'.$val->value.'"';
				if($val->value == "true")
					$script .= "<script>
									document.getElementById('".$id_name."').checked = true;
								</script>";
			}
		}
		
		$display_none = "";
		if(!empty($data['display_none'])) {
			$display_none = ' style="display:none;"';
		}
		
		$return = '<div'.$display_none.'>
						<div class="row c-choice c-choice--checkbox">
							<input class="c-choice__input" id="'.$id_name.'" name="'.$id_name.'" type="checkbox"'.$str_val.' onchange="changeChecked(this)">
							<label class="c-choice__label" for="'.$id_name.'">'.$label.'</label>'.$script.'
						</div>
					</div>';
		return $return;
	}
	
	//селекты
	function fld_select($data) {
		$return = "";
		$label = $data['name'];
		$ch = "";
		$id_name = $data['id_name'];
		if(!empty($data['flag_checked']) && $data['flag_checked'] == 1) {
			$ch = ' data-checked="true"';
		}
		if(empty($data['value_default']))
			return "";
		$val = json_decode($data['value_default']);
		if(empty($val) || empty($val->table))
			return "";
		if(!empty($val->tree)) {
			$select = "SELECT id,parent,title,flags FROM ".$val->table;
		}
		else {
			if(!empty($val->field_name)) {
				$name = $val->field_name;
			}
			else {
				$name = "name";
			}
			$select = "SELECT id,$name FROM ".$val->table;
		}
		//если не весь справочник необходим
		if(!empty($val->current_value)) {
			$select .= " WHERE id IN (".$val->current_value.")";
		}
		$query = mysql_query($select) or die(null);
		$elem = "";
		while($row = mysql_fetch_array($query)) {
			if(!empty($val->tree)) {
				$flag = $row['flags'];
				$name = $row['title'];
				$prnt = $row['parent'];
				if(empty($val->no_full)) {
					while($flag != 1) {
						$sel = "SELECT id,parent,title,flags FROM ".$val->table.' WHERE id='.$prnt;
						$q = mysql_query($sel) or die(null);
						if($r = mysql_fetch_array($q)) {
							$prnt = $r['parent'];
							$flag = $r['flags'];
							$name = $r['title']." ".$name;
						}
						else {
							$flag = 1;
						}
					}
				}
				$elem .= '<option value="'.$row['id'].'">'.$name.'</option>';
			}
			else {
				$elem .= '<option value="'.$row['id'].'">'.$row[$name].'</option>';
			}
		}
		
		if(!empty($elem)) {
			$display_none = "";
			if(!empty($data['display_none'])) {
				$display_none = ' style="display:none;"';
			}
			$return = '<div'.$display_none.'>
						<label class="c-label">'.$label.'</label>
						<div class="row">
							<select class="c-select" id="'.$id_name.'" name="'.$id_name.'"'.$ch.'>'.$elem.'</select>
						</div>
					</div>';
		}
		
		return $return;
	}
	
	//радио кнопки через картинки
	//справочник должен содержать поля id,name,value,path_img
	//такое поле должно быть одно на странице
	function fld_radio_img($data) {
		$return = "";
		$label = $data['name'];
		$ch = "";
		if(!empty($data['flag_checked']) && $data['flag_checked'] == 1) {
			$ch = ' data-checked="true"';
		}
		$id_name = $data['id_name'];
		if(empty($data['value_default']))
			return "";
		$val = json_decode($data['value_default']);
		if(empty($val) || empty($val->table))
			return "";
		
		$select = "SELECT id,name,value,path_img FROM ".$val->table;
		//если не весь справочник необходим
		if(!empty($val->current_value)) {
			$select .= " WHERE id IN (".$val->current_value.")";
		}
		$query = mysql_query($select) or die(null);
		$elem = "";
		$index = 0;
		while($row = mysql_fetch_array($query)) {
			if(($index % 4) === 0) {
				if(empty($elem)) {
					$elem .= '<div class="row row_element">';
				}
				else {
					$elem .= '</div><div class="row row_element">';
				}
			}
			
			if(!empty($val->is_val)) {
				$dataid = $row['value'];
			}
			else {
				$dataid = $row['id'];
			}
			
			$elem .= '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
							<div class="imgTemplate" data-id="'.$dataid.'" style="background: white url('.$row['path_img'].') center no-repeat;background-size: contain;" data-input-id="'.$id_name.'" onclick="checkedRadioImg(this)"></div>
							<div style="text-align: center;"><p class="minitext">'.$row['name'].'</p></div>
						</div>';
			
			$index++;
		}
		if(!empty($elem)) {
			$elem .= '</div>';
			$display_none = "";
			if(!empty($data['display_none'])) {
				$display_none = ' style="display:none;"';
			}
			$return = '<div'.$display_none.'>
							<h3>'.$label.'</h3>
							<div id="'.$id_name.'_block" style="overflow: auto;height:70vh;">
								<input type="text" id="'.$id_name.'" name="'.$id_name.'" style="display:none;"'.$ch.'>'.$elem.'
							</div>
						</div>';
		}
		
		return $return;
	}
	
	//текстовое поле
	function fld_textarea($data) {
		$return = "";
		$label = $data['name'];
		$ch = "";
		$id_name = $data['id_name'];
		if(!empty($data['flag_checked']) && $data['flag_checked'] == 1) {
			$ch = ' data-checked="true"';
		}
		$str_val = "";
		if(!empty($data['value_default'])) {
			$val = json_decode($data['value_default']);
			$str_val .= ' value="'.$val.'"';
		}
		
		$display_none = "";
		if(!empty($data['display_none'])) {
			$display_none = ' style="display:none;"';
		}
		
		$return = '<div'.$display_none.'>
						<label class="c-label">'.$label.'</label>
						<div class="row">
							<textarea class="c-input" id="'.$id_name.'" name="'.$id_name.'"'.$ch.$str_val.'></textarea>
						</div>
					</div>';
		return $return;
	}
?>









