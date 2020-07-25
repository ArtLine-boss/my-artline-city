<?php
    class classes_MaterialInnerSize {
        //вынос
        var $rice = null;
        //края
        var $left = null;
        //сверху/снизу
        var $top = null;
        //количество изделий
        var $count_product = null;
        //ширина
        var $width = null;
        //длина
        var $heigth = null;
        //ид материала
        var $id_mat = null;

        public function __construct($param = null)
        {
            if(empty($param))
                return;
            (!empty($param['vynos'])) ? $this->rice = $param['vynos'] : 0;
            (!empty($param['left'])) ? $this->left = $param['left'] : 0;
            (!empty($param['top'])) ? $this->top = $param['top'] : 0;
            (!empty($param['count_product'])) ? $this->count_product = $param['count_product'] : 0;
            if(!empty($param['size'])) {
                $size = explode('*', $param['size']);
                (!empty(floatval($size[0]))) ? $this->width = floatval($size[0]) : 0;
                (!empty(floatval($size[1]))) ? $this->heigth = floatval($size[1]) : 0;
            }
            (!empty($param['material'])) ? $this->id_mat = $param['material'] : 0;
        }

        //определяем материал, где влазит печатный лист
        public function SelectMaterial($isArray = false) {
            $element = null;
            if($isArray)
                $element = array();

            do {
                $materials = $this->searchMaterialInStock();
                if(count($materials) == 0) {
                    $materials = $this->searchMaterialOutStock();
                }
                if(count($materials) == 0)
                    break;

                $d = -1;
                $k = -1;
                foreach ($materials as $key => $value) {
                    $size = explode('*', $value->M_SIZE);
                    $w_ = (empty($size[0])) ? 0 : floatval($size[0]);
                    $h_ = (empty($size[1])) ? 0 : floatval($size[1]);
                    $k = $this->LayoutOnSheet($w_, $h_, $this->width, $this->heigth, 0, 0, 0, true);
                    if($k['count'] > 0) {
                        $d_ = $w_*$h_ - $this->width * $this->heigth * floatval($k['count']);
                        $value->count_sheet = $k;
                        if($d == -1) {
                            $d = $d_;
                            $k = intval($k['count']);
                            if($isArray)
                                $element[] = $value;
                            else
                                $element = $value;
                        }
                        else if($d_ < $d) {
                            $d = $d_;
                            $k = intval($k['count']);
                            if($isArray)
                                $element[] = $value;
                            else
                                $element = $value;
                        }
                        else if($d_ == $d) {
                            if($k > intval($k['count'])) {
                                $d = $d_;
                                $k = intval($k['count']);
                                if($isArray)
                                    $element[] = $value;
                                else
                                    $element = $value;
                            }
                        }
                    }
                }

            } while(false);

            return $element;
        }

        //материалы в наличии
        protected function searchMaterialInStock() {
            $list = array();

            do {
                if(empty($this->id_mat))
                    break;
                $material = new classes_MaterialAttr();
                $where = array(
                    'sql' => 'id_tree=:id_tree AND M_KOL_ALL>0',
                    'param' => array(
                        'id_tree' => $this->id_mat,
                    ),
                );
                $materials = $material->loadAll($where, 'ID');
                if(count($materials) == 0)
                    break;
                $list = $this->checkArrayMatAttr($materials);
            } while(false);

            return $list;
        }

        //когда нет материала в наличии
        protected function searchMaterialOutStock() {
            $list = array();

            do {
                if(empty($this->id_mat))
                    break;
                $material = new classes_MaterialAttr();
                $where = array(
                    'sql' => 'id_tree=:id_tree',
                    'param' => array(
                        'id_tree' => $this->id_mat,
                    ),
                );
                $materials = $material->loadAll($where, 'ID', 1, 1);
                if(count($materials) == 0)
                    break;
                $list = $this->checkArrayMatAttr($materials);
            } while(false);

            return $list;
        }

        //обработка массива размеров
        protected function checkArrayMatAttr($arr) {
            $list = array();

            do {
                if(empty($arr))
                    break;
                foreach($arr as $key => $value) {
                    $size = explode('*', $value->M_SIZE);
                    if(!empty($size[0]) && !empty($size[1])) {
                        $list[] = $value;
                    } else if(!empty($size)) {
                        $value->widescreen_lenght = $this->returnWidescreen($value->M_SIZE);
                        $value->M_SIZE = $value->M_SIZE . "*" . $value->widescreen_lenght;
                        $value->widescreen = true;
                        $list[] = $value;
                    }
                }
            } while(false);

            return $list;
        }

        //функция определения максимального количества листов на большом листе
        protected function LayoutOnSheet($w, $h, $w_, $h_, $f_v, $f_g, $v, $max = false) {
            /*
                $w - длина родительского листа
                $h - высота родительского листа
                $w_ - длина листа
                $h_ - высота листа
                $f_v - поля принтера по вертикали (сверху, снизу)
                $f_v - поля принтера по горизонтали (слева, справа)
                $v - вынос цвета
            */
            $echo = 0;
            $w = floatval($w);
            $h = floatval($h);
            $w_ = floatval($w_);
            $h_ = floatval($h_);
            $f_v = floatval($f_v);
            $f_g = floatval($f_g);
            $v = floatval($v);
            //4 варианта раскладки, выбираем оптимальный
            $t1 = $this->ReturnCountLayoutOnSheet($w - $f_g, $h - $f_v, $w_ + 2*$v, $h_ + 2*$v);
            $t2 = $this->ReturnCountLayoutOnSheet($w - $f_g, $h - $f_v, $h_ + 2*$v, $w_ + 2*$v);
            $t3 = $this->ReturnCountLayoutOnSheet($h - $f_g, $w - $f_v, $w_ + 2*$v, $h_ + 2*$v);
            $t4 = $this->ReturnCountLayoutOnSheet($h - $f_g, $w - $f_v, $h_ + 2*$v, $w_ + 2*$v);
            $m = array(
                'count' => $t1,
                'width_parent' => $w,
                'height_parent' => $h,
                'width' => $w_,
                'height' => $h_,
            );
            //определяем максимальный
            if($t2 > $m['count']) {
                $m = array(
                    'count' => $t2,
                    'width_parent' => $w,
                    'height_parent' => $h,
                    'width' => $h_,
                    'height' => $w_,
                );
            }
            if($t3 > $m['count']) {
                $m = array(
                    'count' => $t3,
                    'width_parent' => $h,
                    'height_parent' => $w,
                    'width' => $w_,
                    'height' => $h_,
                );
            }
            if($t4 > $m['count']) {
                $m = array(
                    'count' => $t4,
                    'width_parent' => $h,
                    'height_parent' => $w,
                    'width' => $h_,
                    'height' => $w_,
                );
            }

            if($max) {
                $k_w = empty($m['width']) ? 0 : floor($m['width_parent'] / $m['width']);
                $d_w = $m['width_parent'] - $k_w * $m['width'];
                $k_h = empty($m['height']) ? 0 : floor($m['height_parent'] / $m['height']);
                $d_h = $m['height_parent'] - $k_h * $m['height'];
                if($d_w >= $m['height']) {
                    $m['count'] += empty($m['width']) ? 0 : floor($m['height_parent'] / $m['width']);
                    $m['delta_width'] = $k_w * $m['width'];
                } elseif ($d_h >= $m['width']) {
                    $m['count'] += empty($m['height']) ? 0 : floor($m['width_parent'] / $m['height']);
                    $m['delta_height'] = $k_h * $m['height'];
                }
            }

            return $m;
        }

        //раскладка по 4м параметрам... возвращает количество
        protected function ReturnCountLayoutOnSheet($w, $h, $w_, $h_) {
            if(empty($w_) || empty($h_))
                return 0;
            return intval(floor($w/$w_) * floor($h/$h_));
        }

        //определние длины для широкоформатки
        protected function returnWidescreen($length) {
            if(empty($length))
                return 0;
            $w = $this->width + 2 * $this->rice;
            $h = $this->heigth + 2 * $this->rice;
            if(empty($w) || empty($h))
                return 0;
            $f_l = $this->left;
            $f_t = $this->top;
            $count = $this->count_product;
            //погонный метр считаем с двумя полями
            $result = 2 * $f_t;
            //доступная ширина без полей
            $length = $length - 2 * $f_l;
            //определяем минимальный размер и максимальный
            $max = $w;
            $min = $h;
            if($max < $min)
                $max+=+$min-$min=$max;
            //по минимально стороне впихиваем
            $count_g = floor($length / $min);
            $full_col = floor($count / $count_g);
            //остаток элементов
            $mod = $count - $full_col * $count_g;
            //добавляем к результату
            $result += $full_col * $max;
            //допихиваем
            while(true) {
                if($mod <= 0)
                    break;
                //если влезло в одну строку
                if(($max * $mod) <= $length) {
                    $result += $min;
                    break;
                }
                //иначе
                else {
                    //если не выгодно впихивать строку с наименьшим, то делаем типа изначальную расскладку
                    if(2 * $min > $max) {
                        $result += $max;
                        break;
                    }
                    //иначе пихаем с наименьшим и изменяем остаток
                    else {
                        $result += $min;
                        $mod = $mod - floor($length / $max);
                    }
                }
            }
            return $result;
        }
    }
?>
