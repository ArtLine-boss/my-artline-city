<?php
class entity_whBlocks extends core_DBObject
{
    /**
     * Наименование
     * @var string|null
     */
    public $name = null;
    /**
     * Путь к фону
     * @var string|null
     */
    public $image = null;

    public function __construct()
    {
        parent::__construct('wh_blocks', 'ID', 'entity_whBlocks');
    }
}