<?php


class core_Object
{
    public function __construct()
    {

    }

    public function bind($value = null) {
        $msg = null;

        do {
            if(empty($value)) {
                $msg = "Пустые входные данные";
                break;
            }
            if(!is_object($value) && !is_array($value)) {
                $msg = "Неверная структура у входных данных";
                break;
            }

            foreach ($value as $k => $v) {
                if(property_exists($this, $k)) {
                    if(is_object($v) && is_object($this->$k)) {
                        $obj = $this->$k;
                        if(!method_exists($obj, 'bind') || null !== ($msg = $obj->bind($v))) {
                            $this->$k = $v;
                        }
                    } else {
                        $this->$k = $v;
                    }
                }
            }
        } while(false);

        return $msg;
    }
}