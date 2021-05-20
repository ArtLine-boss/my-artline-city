$(function() {
    $("#tableAccordAM").DataTable({
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
        },
        "order": [[ 0, 'asc' ]],
        'columnDefs': [{
            'targets': "no-sort",
            'sorting': false,
            'searching': false,
        }],
    });

    $("#tableAccordMA").DataTable({
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
        },
        "order": [[ 0, 'asc' ]],
        'columnDefs': [{
            'targets': "no-sort",
            'sorting': false,
            'searching': false,
        }],
    });
});

function addAccord() {
    CreateModalWindow('add', 'Добавление соответствия пользователей', 'addAccordSub');

    let opts = '<option value="0">ОТСУТСТВУЕТ</option>';
    for (let i in ALL_USERS) {
        opts += '<option value="' + ALL_USERS[i].id + '">' + ALL_USERS[i].name + '</option>';
    }

    let html = '';
    html += '<label class="field__label" for="block_accord_comp">Тип соответствия</label>';
    html += '<div class="c-choice c-choice--radio" id="block_accord_comp">\n' +
        '                            <input class="c-choice__input" id="accord_comp1" name="accord_comp" type="radio" onchange="changeAccordComp()">\n' +
        '                            <label class="c-choice__label" for="accord_comp1">АртЛайн - Мечта</label>\n' +
        '                        </div>\n' +
        '\n' +
        '                        <div class="c-choice c-choice--radio">\n' +
        '                            <input class="c-choice__input" id="accord_comp2" name="accord_comp" type="radio" onchange="changeAccordComp()">\n' +
        '                            <label class="c-choice__label" for="accord_comp2">Мечта - АртЛайн</label>\n' +
        '                        </div>';
    html += '<div id="block_accord_users" style="display: none"><label class="field__label" for="block_accord_comp">Пользователь 1й компании</label><select id="accord_user1" onchange="changeAccordUser1(this)">' + opts + '</select>';
    html += '<div id="block_accord_user2"></div></div>';

    $('#add-body').append(html);

    $('#accord_user1').select2({
        width: '100%',
    });
}

/**
 * отображение 2го селекта юзеров
 * @param data
 */
function viewAccordUser2(data = [])
{
    if(!document.getElementById('block_accord_user2')) return;
    removeElement('tmp_block_accord_user2');
    let opts = '<option value="0">ОТСУТСТВУЕТ</option>';
    for (let i in ALL_USERS) {
        if(data.indexOf(ALL_USERS[i].id) >= 0) continue;
        if(data.indexOf(parseInt(ALL_USERS[i].id)) >= 0) continue;
        opts += '<option value="' + ALL_USERS[i].id + '">' + ALL_USERS[i].name + '</option>';
    }
    $('#block_accord_user2').append('<div id="tmp_block_accord_user2"><label class="field__label" for="block_accord_comp">Пользователь 2й компании</label><select id="accord_user2">' + opts + '</select></div>');
    $('#accord_user2').select2({
        width: '100%',
    });
}

function changeAccordComp() {
    $('#block_accord_users').prop('style', 'display: none');
    removeElement('tmp_block_accord_user2');
    if(!$('#accord_comp1').prop("checked") && !$('#accord_comp2').prop("checked")) return;
    $('#block_accord_users').prop('style', 'display: block');
}

function changeAccordUser1(e) {
    if($(e).val() <= 0) {
        removeElement('tmp_block_accord_user2');
        return;
    }
    let accordType = 0;
    if($('#accord_comp2').prop("checked")) accordType = 1;
    sendAjax('m=settings&u=accord&a=AjaxSelectedUser1', {'id': $(e).val(), 'accordType': accordType}, 'answerChangeAccordUser1');
}

function answerChangeAccordUser1(data) {
    viewAccordUser2(data);
}

function addAccordSub() {
    let accordType = 0;
    if($('#accord_comp2').prop("checked")) accordType = 1;
    let elem = {
        'user1': $('#accord_user1').val(),
        'user2': $('#accord_user2').val(),
        'accordType': accordType,
    }
    sendAjax('m=settings&u=accord&a=AjaxSaveAccord', elem, 'reload_page');
}

function reload_page() {
    location.href = location.href;
}

function cancelAccord(id) {
    sendAjax('m=settings&u=accord&a=AjaxCancelAccord', {'id': id}, 'reload_page');
}