<?php


class core_Object
{
    protected $intFields;
    protected $floatFields;

    public function __construct()
    {
        $this->intFields = [];
        $this->floatFields = [];
    }

    /**
     * @param array $intFields
     */
    public function setIntFields($intFields)
    {
        $this->intFields = $intFields;
    }

    /**
     * @param array $floatFields
     */
    public function setFloatFields($floatFields)
    {
        $this->floatFields = $floatFields;
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
                            $this->$k = $this->toType($k, $v);
                        }
                    } else {
                        $this->$k = $this->toType($k, $v);
                    }
                }
            }
        } while(false);

        return $msg;
    }

    protected function toType($key, $val) {
        if(in_array($key, $this->intFields)) {
            return intval($val);
        } else if(in_array($key, $this->floatFields)) {
            return floatval(str_replace(',', '.', $val));
        }

        return empty($val) ? $val : strval($val);
    }

    public function set($key, $val) {
        $this->$key = $this->toType($key, $val);
    }
}