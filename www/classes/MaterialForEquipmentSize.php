<?php
    class classes_MaterialForEquipmentSize extends classes_MaterialInnerSize {
        var $equipment = null;
        var $hash = null;

        public function __construct($param = null)
        {
            parent::__construct($param);
            $this->hash = $param;
            $this->equipment = new classes_equipment();
            $this->equipment->loadById($param['val']);
            if($this->equipment->l_use != 1)
                $this->equipment = null;
            $this->left = (!$param['pol']) ? 0 : floatval($this->equipment->ladnr);
            $this->top = (!$param['pol']) ? 0 : floatval($this->equipment->uandd);
        }

        public function SelectMaterialForEquipment() {
            $list = array();

            do {
                if(empty($this->equipment->FORMAT))
                    break;
                $size_print = new classes_SizePrint();
                $where = array(
                    'sql' => 'ID IN (' . $this->equipment->FORMAT . ')',
                    'param' => null
                );
                $size_prints = $size_print->loadAll($where);
                if(count($size_prints) == 0)
                    break;

                $tmp = array();
                if(empty($this->width) || empty($this->heigth))
                    $tmp = $size_prints;
                else {
                    foreach ($size_prints as $key => $value) {
                        $size = explode('*', $value->SIZE);
                        $w = (empty($size[0])) ? 0 : floatval($size[0]);
                        $h = (empty($size[1])) ? 0 : floatval($size[1]);

                        if (($this->width <= $w && $this->heigth <= $h) || ($this->width <= $h && $this->heigth <= $w)) {
                            $value->count_sheet = $this->LayoutOnSheet($w, $h, $this->width, $this->heigth, $this->top, $this->left, $this->rice, true);
                            if ($value->count_sheet['count'] == 0)
                                continue;
                            $tmp[] = $value;
                        }
                    }
                }

                $innerMat = new classes_MaterialInnerSize($this->hash);
                $innerMatList = $innerMat->SelectMaterial(true);

                $this->SizePrintInMaterial($tmp, $innerMatList);
                $list = $tmp;

            } while(false);

            return $list;
        }

        protected function SizePrintInMaterial(&$size, $mat) {
            do {
                if(empty($size) || !is_array($size) || empty($mat) || !is_array($mat))
                    break;
                $list = $size;
                $size = array();
                foreach ($list as $key => $value) {
                    $s1 = explode('*', $value->SIZE);
                    $w1 = $s1[0];
                    $h1 = $s1[1];
                    if(empty($w1) || empty($h1)) {
                        continue;
                    }
                    foreach ($mat as $k => $v) {
                        $s2 = explode('*', $v->M_SIZE);
                        $w2 = $s2[0];
                        $h2 = $s2[1];
                        if(empty($w2) || empty($h2)) {
                            continue;
                        }
                        if(($w1 <= $w2 && $h1 <=$h2) || ($w1 <= $h2 && $h1 <=$w2)) {
                            $size[] = $value;
                            break;
                        }
                    }
                }
            } while(false);
        }
    }
?>
