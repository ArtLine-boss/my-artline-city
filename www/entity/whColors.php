<?php
class entity_whColors extends core_DBObject
{
    /**
     * Наименование
     * @var string|null
     */
    public $name = null;
    /**
     * Значение цвета
     * @var string|null
     */
    public $value = null;

    public function __construct()
    {
        parent::__construct('wh_colors', 'ID', 'entity_whColors');
    }
}