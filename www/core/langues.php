<?php


class core_langues extends core_DBObject
{
    public function __construct()
    {
        parent::__construct('langues', 'id', 'core_langues');
    }

    public function get($txt) {
        $result = $txt;

        do {
            $key = isset($_GET['m']) ? strval($_GET['m']) : null;
            $key2 = isset($_GET['u']) && !empty($key) ? $key . '_' . strval($_GET['u']) : null;
            $key3 = isset($_GET['a']) && !empty($key2) ? $key2 . '_' . strval($_GET['a']) : null;

            $sql = "SELECT `" . $this->getTable() . "`.`ruVersion` FROM `" . $this->getTable() . "` 
                    WHERE `" . $this->getTable() . "`.`module` = '{$key}' AND `" . $this->getTable() . "`.`key` = '{$txt}'";

            $sql2 = "SELECT `" . $this->getTable() . "`.`ruVersion` FROM `" . $this->getTable() . "` 
                    WHERE `" . $this->getTable() . "`.`module` = '{$key2}' AND `" . $this->getTable() . "`.`key` = '{$txt}'";

            $sql3 = "SELECT `" . $this->getTable() . "`.`ruVersion` FROM `" . $this->getTable() . "` 
                    WHERE `" . $this->getTable() . "`.`module` = '{$key3}' AND `" . $this->getTable() . "`.`key` = '{$txt}'";

            $_val3 = $this->select($sql3);
            if(count($_val3) > 0) {
                $result = $_val3[0]['ruVersion'];
                break;
            }

            $_val2 = $this->select($sql2);
            if(count($_val2) > 0) {
                $result = $_val2[0]['ruVersion'];
                break;
            }

            $_val = $this->select($sql);
            if(count($_val) > 0) {
                $result = $_val[0]['ruVersion'];
                break;
            }

        } while(false);

        return $result;
    }
}