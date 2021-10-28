<?php
class entity_whEditor extends core_DBObject
{
    /**
     * Вебхук
     * @var int|null
     */
    public $webhook_id = null;
    /**
     * Блок
     * @var int|null
     */
    public $block_id = null;
    /**
     * Элемент
     * @var int|null
     */
    public $element_id = null;
    /**
     * Цвет
     * @var int|null
     */
    public $color_id = null;
    /**
     * URL к картинке
     * @var string|null
     */
    public $image = null;

    public function __construct()
    {
        parent::__construct('wh_editor', 'ID', 'entity_whEditor');
    }

    /**
     * @return entity_webhooks
     */
    public function getWebhook() {
        return entity_webhooks::oid($this->webhook_id);
    }

    /**
     * @return entity_whBlocks
     */
    public function getBlock() {
        return entity_whBlocks::oid($this->block_id);
    }

    /**
     * @return entity_whElements
     */
    public function getElement() {
        return entity_whElements::oid($this->element_id);
    }

    /**
     * @return entity_whColors
     */
    public function getColor() {
        return entity_whColors::oid($this->color_id);
    }
}