<?php
class entity_whElements extends core_DBObject
{
    /**
     * Наименование
     * @var string|null
     */
    public $name = null;

    public function __construct()
    {
        parent::__construct('wh_elements', 'ID', 'entity_whElements');
    }
}