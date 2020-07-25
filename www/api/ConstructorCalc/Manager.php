<?php
class api_ConstructorCalc_Manager extends core_Object {
    public $Materials = array();
    public $Sizes = array();
    public $Colors = array();
    public $Quantitys = array();

    public function __construct()
    {
        $this->Materials = array();
        $this->Sizes = array();
        $this->Colors = array();
        $this->Quantitys = array();
    }

    public function bind($value = null) {
        $msg = null;

        do {
            if(null !== ($msg = $this->getMaterials())) {
                break;
            }

            if(null !== ($msg = $this->getSizes())) {
                break;
            }

            if(null !== ($msg = $this->getColors())) {
                break;
            }

            if(null !== ($msg = $this->getQuantitys())) {
                break;
            }

        } while(false);

        return $msg;
    }

    protected function getMaterials() {
        $msg = null;

        do {
            $this->Materials = array(
                0 => array(
                    'value' => "22",
                    'text' => "Белая матовая бумага 350 гр/м",
                ),
                1 => array(
                    'value' => "373",
                    'text' => "Бумага \"лен\", 300 гр/м",
                ),
                2 => array(
                    'value' => "163",
                    'text' => "Бумага с перламутровым эффектом 300 гр/м",
                ),
                3 => array(
                    'value' => "39",
                    'text' => "Премиум бумага TouchCover 301 гр/м",
                ),
            );
        } while(false);

        return $msg;
    }

    protected function getSizes() {
        $msg = null;

        do {
            $this->Sizes = array(
                0 => array(
                    'value' => "90*50",
                    'text' => "90x50",
                ),
                1 => array(
                    'value' => "85*55",
                    'text' => "85x55",
                ),
                2 => array(
                    'value' => "90*55",
                    'text' => "90x55",
                ),
                3 => array(
                    'value' => "170*55",
                    'text' => "170x55 (горизонтально складные визитки)",
                ),
                4 => array(
                    'value' => "110*85",
                    'text' => "110x85 (вертикально складные видитки)",
                ),
            );
        } while(false);

        return $msg;
    }

    protected function getColors() {
        $msg = null;

        do {
            $this->Colors = array(
                0 => array(
                    'value' => "1",
                    'text' => "Односторонняя полноцветная печать (4+0)",
                ),
                1 => array(
                    'value' => "45",
                    'text' => "Двухсторонняя полноцветная печать (4+4)",
                ),
            );
        } while(false);

        return $msg;
    }

    protected function getQuantitys() {
        $msg = null;

        do {
            $this->Quantitys = array(
                0 => array(
                    'value' => "10",
                    'text' => "10",
                ),
                1 => array(
                    'value' => "25",
                    'text' => "25",
                ),
                2 => array(
                    'value' => "50",
                    'text' => "50",
                ),
                3 => array(
                    'value' => "75",
                    'text' => "75",
                ),
                4 => array(
                    'value' => "100",
                    'text' => "100",
                ),
                5 => array(
                    'value' => "150",
                    'text' => "150",
                ),
                6 => array(
                    'value' => "200",
                    'text' => "200",
                ),
                7 => array(
                    'value' => "250",
                    'text' => "250",
                ),
                8 => array(
                    'value' => "300",
                    'text' => "300",
                ),
                9 => array(
                    'value' => "400",
                    'text' => "400",
                ),
                10 => array(
                    'value' => "500",
                    'text' => "500",
                ),
                11 => array(
                    'value' => "1000",
                    'text' => "1000",
                ),
            );
        } while(false);

        return $msg;
    }
}
?>