<?php
abstract class popup_Abstract {
    protected function getParametrs() {return null;}
    protected function getEcho($param = null) {return '';}
    final public function view() {
        $params = $this->getParametrs();
        if(empty($params) || (is_array($params) && count($params) == 0)) {
            return;
        } else {
            echo $this->getEcho($params);
        }
    }
}
?>