<?php
class factorys_classes {
    static function getObject($name = null) {
        $class = null;

        do {
            if(empty($name) || !is_string($name)) {
                break;
            }
            if(!class_exists($name)) {
                break;
            }
            $class = new $name();
        } while(false);

        return $class;
    }
}
?>