<?php
    class core_Dto implements core_iDto {
        var $Msg = null;
        var $Data = null;
        var $ReloadURL = null;

        public function __construct()
        {
        }

        public function getMsg()
        {
            // TODO: Implement getMsg() method.
            return $this->Msg;
        }
        public function setMsg($value)
        {
            // TODO: Implement setMsg() method.
            $this->Msg = $value;
        }

        public function getData()
        {
            // TODO: Implement getData() method.
            return $this->Data;
        }

        public function setData($value)
        {
            // TODO: Implement setData() method.
            $this->Data = $value;
        }

        public function getReloadURL()
        {
            // TODO: Implement getReloadURL() method.
            return $this->ReloadURL;
        }
        public function setReloadURL($value)
        {
            // TODO: Implement setReloadURL() method.
            $this->ReloadURL = $value;
        }
    }
?>
