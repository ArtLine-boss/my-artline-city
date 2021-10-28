<?php
class webhooks_editor_books_Dto
{
    /**
     * Вебхук
     * @var entity_webhooks|null
     */
    public $webhook = null;
    /**
     * Блок
     * @var entity_whBlocks|null
     */
    public $block = null;
    /**
     * Элемент
     * @var entity_whElements|null
     */
    public $element = null;
    /**
     * Цвет
     * @var entity_whColors|null
     */
    public $color = null;
    /**
     * URL к картинке
     * @var string|null
     */
    public $image = null;

    /**
     * Строитель класса
     * @param int $editor_id
     * @return static
     */
    public static function build($editor_id) {
        $class = new static();
        $editor = entity_whEditor::oid($editor_id);
        $class->webhook = $editor->getWebhook();
        $class->block = $editor->getBlock();
        $class->element = $editor->getElement();
        $class->color = $editor->getColor();
        $class->image = $editor->image;
        return $class;
    }
}