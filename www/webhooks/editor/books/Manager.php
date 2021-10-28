<?php
class webhooks_editor_books_Manager extends webhooks_Manager
{
    const BLOCK_COVER = 1;
    const BLOCK_FACE = 2;

    const ELEMENT_SKIN = 1;
    const ELEMENT_SPRING = 2;
    const ELEMENT_BOOKMARK = 3;
    const ELEMENT_STROKE = 4;
    const ELEMENT_ELASTIC = 5;
    const ELEMENT_PEN = 6;

    protected function getBody()
    {
        return '
                <div class="editor">
                   <div class="editor-block">
                        <div class="editor-block-body"></div>
                        <div class="editor-block-menu">
                            <div class="editor-block-menu-header"><h1>Ваш ежедневник</h1></div>
                            <div class="c-toggle u-mb-small">
                                <div class="c-toggle__btn is-active" onclick="changeSkin(' . static::BLOCK_COVER . ')">
                                    <label class="c-toggle__label" for="cover-skin">
                                        <input class="c-toggle__input" id="cover-skin" name="skins" type="radio" checked>Снаружи
                                    </label>
                                </div>
        
                                <div class="c-toggle__btn" onclick="changeSkin(' . static::BLOCK_FACE . ')">
                                    <label class="c-toggle__label" for="face-skin">
                                        <input class="c-toggle__input" id="face-skin" name="skins" type="radio">Внутри
                                    </label>
                                </div>
                            </div>
                            <div class="editor-block-menu-body">
                                <div class="editor-block-menu-body-element">
                                    <label>Обложка снаружи</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_SKIN . '-' . static::BLOCK_COVER . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_SKIN . '-' . static::BLOCK_COVER . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Обложка внутри</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_SKIN . '-' . static::BLOCK_FACE . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_SKIN . '-' . static::BLOCK_FACE . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Пружина</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_SPRING . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_SPRING . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Ляссе</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_BOOKMARK . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_BOOKMARK . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Строчка</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_STROKE . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_STROKE . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Резинка</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_ELASTIC . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_ELASTIC . '"></div>
                                <div class="editor-block-menu-body-element">
                                    <label>Крепление для ручки</label>
                                    <div class="editor-block-menu-body-element-color" id="selected-element-colors-' . static::ELEMENT_PEN . '"></div>
                                </div>
                                <div class="editor-block-menu-body-element-colors" id="element-colors-' . static::ELEMENT_PEN . '"></div>
                            </div>
                            <div class="editor-block-menu-btn">Заказать</div>
                        </div>
                   </div>             
                </div>
                ';
    }

    protected function getFooterScript()
    {
        return '
            <script>
                $(document).ready(function () {
                    let singleton = new Singleton();
                    singleton.setInfo(' . json_encode($this->getInfo()) . ');
                    singleton.setBlockCover(' . static::BLOCK_COVER. ');
                    singleton.setBlockFace(' . static::BLOCK_FACE. ');
                    singleton.setBlockCurrent(' . static::BLOCK_COVER . ');
                    
                    singleton.setElement(' . static::ELEMENT_SKIN . ', true);
                    singleton.setElement(' . static::ELEMENT_SPRING . ');
                    singleton.setElement(' . static::ELEMENT_BOOKMARK . ');
                    singleton.setElement(' . static::ELEMENT_STROKE . ');
                    singleton.setElement(' . static::ELEMENT_ELASTIC . ');
                    singleton.setElement(' . static::ELEMENT_PEN . ');
                    
                    singleton.view();
                });
            </script>
        ';
    }

    /**
     * Весь блок инфы для редактора
     * @return array
     */
     protected function getInfo() {
        $editors = entity_whEditor::all(['sql' => '`webhook_id` = :wh_id', 'param' => ['wh_id' => $this->getWebhook()->getId()]]);
        $info = [];
        foreach ($editors as $editor) {
            /** @var entity_whEditor $editor */
            $info[] = webhooks_editor_books_Dto::build($editor->getId());
        }
        return $info;
     }
}