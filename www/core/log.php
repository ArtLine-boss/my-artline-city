<?php
/*
 * Класс логирования
 */
class core_log extends core_Object {
    protected $_filename = null;

    public function __construct($filename = null) {
        if(empty($filename) || !is_string($filename))
            return;
        $this->_filename = $_SERVER['DOCUMENT_ROOT'] . '/www/' . $filename;
    }

    /**
     * @return string|null
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    public function store($msg = null, $byDate = true) {
        do {
            if(empty($this->_filename) || !is_string($this->_filename))
                return;
            if(empty($msg) || !is_string($msg))
                break;
            $msg = ($byDate ? API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT) . "\t" : '') . $msg . "\r\n";
            if(!file_exists($this->_filename)) {
                $fp = fopen($this->_filename, "w");
            } else {
                $fp = fopen($this->_filename, "a");
            }
            fwrite($fp, $msg);
            fclose($fp);
        } while(false);
    }
}
?>