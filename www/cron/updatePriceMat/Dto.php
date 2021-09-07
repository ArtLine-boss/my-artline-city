<?php
class cron_updatePriceMat_Dto extends core_Object
{
    public $matId;
    public $matName;
    public $price;
    public $currentQ;
    public $ttnTotal;
    public $ttnSum;
    public $ttnNum;
    public $ttnDate;
    public $newPrice;
    public $currency;

    const PERCENT = 10;

    public function __construct()
    {
        $this->setIntFields(['matId']);
        $this->setFloatFields([
            'price',
            'currentQ',
            'ttnTotal',
            'ttnSum',
            'newPrice',
            'currency'
        ]);
    }

    public function bind($data) {
        parent::bind($data);
        if(!empty($this->ttnTotal) && !empty($this->ttnDate)) {
            $settings_attr = entity_settingsAttr::getValByDate(2, $this->ttnDate);
            if($settings_attr->getInit() && !empty($settings_attr->VAL)) {
                $this->set('currency', $settings_attr->VAL);
                $this->set('newPrice', ceil($this->ttnSum * 100 * (1 + (static::PERCENT / 100)) / $this->ttnTotal * $this->currency) / 100);
            }
        }
    }

    public function isUpdate() {
        return $this->price < $this->newPrice;
    }
}
?>