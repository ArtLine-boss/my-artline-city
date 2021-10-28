const Singleton = (function() {
    let instance;

    // Приватные свойства
    let info;
    let blockCover, blockFace, blockCurrent;
    let elements;

    // Конструктор
    function Singleton() {
        if (instance) return instance;
        instance = this;
    }

    // Публичные методы
    Singleton.prototype.setInfo = function(inf) {
        info = inf;
    }
    Singleton.prototype.setBlockCover = function(id) {
        blockCover = id;
    }
    Singleton.prototype.setBlockFace = function(id) {
        blockFace = id;
    }
    Singleton.prototype.setBlockCurrent = function(id) {
        blockCurrent = id;
    }
    Singleton.prototype.setElement = function(id, is_block = false) {
        if(!elements || !Array.isArray(elements)) {
            elements = new Array();
        }
        let colors = info.filter(inf => inf.block.ID == blockCover && inf.element.ID == id);
        let dv_id = 'element-colors-' + id;
        if(is_block) {
            elements[id] = new Array();
            elements[id][blockCover] = {color: colors, currentColor: -1};
            $('#' + dv_id + '-' + blockCover).append('<div class="editor-block-menu-body-element-color-value active" onclick="clickColor(' + id + ',-1,' + blockCover + ')"></div>');
            for(let i in colors) {
                $('#' + dv_id + '-' + blockCover).append('<div class="editor-block-menu-body-element-color-value" style="background-color: ' + colors[i].color.value + '" onclick="clickColor(' + id + ',' + colors[i].color.ID + ',' + blockCover + ')"></div>');
            }
            let colors2 = info.filter(inf => inf.block.ID == blockFace && inf.element.ID == id);
            elements[id][blockFace] = {color: colors2, currentColor: -1};
            $('#' + dv_id + '-' + blockFace).append('<div class="editor-block-menu-body-element-color-value active" onclick="clickColor(' + id + ',-1,' + blockFace + ')"></div>');
            for(let i in colors2) {
                $('#' + dv_id + '-' + blockFace).append('<div class="editor-block-menu-body-element-color-value" style="background-color: ' + colors2[i].color.value + '" onclick="clickColor(' + id + ',' + colors2[i].color.ID + ',' + blockFace + ')"></div>');
            }
        } else {
            elements[id] = {color: colors, currentColor: -1};
            $('#' + dv_id).append('<div class="editor-block-menu-body-element-color-value active" onclick="clickColor(' + id + ',-1)"></div>');
            for(let i in colors) {
                $('#' + dv_id).append('<div class="editor-block-menu-body-element-color-value" style="background-color: ' + colors[i].color.value + '" onclick="clickColor(' + id + ',' + colors[i].color.ID + ')"></div>');
            }
        }
    }
    Singleton.prototype.setCurrentColor = function(element_id, color_id, block_id = -1) {
        let id = 'selected-element-colors-' + element_id;
        if(block_id > 0) {
            id += '-' + block_id;
        }
        $('#' + id).removeAttr('style');

        if(elements[element_id]) {
            let block = null;
            if(elements[element_id].currentColor) {
                elements[element_id].currentColor = color_id;
                block = elements[element_id].color.find(bl => bl.color.ID == color_id);
            } else if(elements[element_id][block_id] && elements[element_id][block_id].currentColor) {
                elements[element_id][block_id].currentColor = color_id;
                block = elements[element_id][block_id].color.find(bl => bl.color.ID == color_id);
            }
            if(block && block.color) {
                $('#' + id).attr('style', 'background-color: ' + block.color.value);
            }
        }
    }

    Singleton.prototype.getBlockCover = function() {
        return blockCover;
    }
    Singleton.prototype.getBlockFace = function() {
        return blockFace;
    }
    Singleton.prototype.view = function() {
        add_block();
        for(let i in elements) {
            add_element(i);
        }
    }

    // Приватные методы
    let review = function() {
        $('.editor-block').removeClass('face');
        if(blockCurrent == blockFace) {
            $('.editor-block').addClass('face');
        }
    }

    let add_block = function(add_class = '') {
        $('.editor-block-body').empty();
        let block = info.find(bl => bl.block.ID == blockCurrent).block;
        if(block && block.image) {
            review();
            $('.editor-block-body').append('<div class="editor-block-body-background' + (add_class != '' ? ' ' + add_class : '')
                + '" style="background-image: url(\'' + block.image + '\')"></div>');
        }
    }

    let add_element = function(element_id, add_class = '') {
        let color = elements[element_id];
        let color_id = -1;
        if(color.currentColor) {
            color_id = color.currentColor;
        } else if(color[blockCurrent] && color[blockCurrent].currentColor) {
            color_id = color[blockCurrent].currentColor;
        }
        let element = info.find(elm => elm.block.ID == blockCurrent && elm.element.ID == element_id && elm.color.ID == color_id);
        if(element && element.image) {
            let dv_element = $('.editor-block-body-element');
            let dv_add = '<div class="editor-block-body-element' + (add_class != '' ? ' ' + add_class : '')
                + '" style="background-image: url(\'' + element.image + '\')"></div>';
            if(dv_element.length > 0) {
                $(dv_element[dv_element.length - 1]).append(dv_add);
            } else {
                $('.editor-block-body-background').append(dv_add);
            }
        }
    }

    return Singleton;
})();

$(document).ready(function (){
    $('.editor-block-menu-body-element-color').on('click', function() {
        let idSplit = this.id.split('-');
        let id = '';
        for(let i in idSplit) {
            if($.isNumeric(idSplit[i])) {
                id += (id == '' ? '' : '-') + idSplit[i];
            }
        }
        let elem = document.querySelector('#element-colors-' + id)
        let is_show = elem.classList.contains('show');
        $('.editor-block-menu-body-element-colors.show').removeClass('show');
        if(!is_show) {
            $('#element-colors-' + id).addClass('show');
        }
    });
});

function changeSkin(block_id) {
    let singleton = new Singleton();
    singleton.setBlockCurrent(block_id);
    singleton.view();
}

function clickColor(element_id, color_id, block_id = -1) {
    let singleton = new Singleton();
    singleton.setCurrentColor(element_id, color_id, block_id);
    singleton.view();
}