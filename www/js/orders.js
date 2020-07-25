function clickOrder(_order, e) {
    removeElement('orderItem_tr');

    let data = sendAjax('m=orders&a=AjaxOrderItem', {'orderId': _order,}, null, false, true);

    if(!data.fields || !data.items) {
        return;
    }

    let colspan = e.getElementsByTagName('td').length;

    let tbl = '<tr class="c-table__row" id="orderItem_tr"><td colspan="' + colspan + '"><div class="c-table-responsive@desktop"><table class="c-table" id="orderItem"><thead class="c-table__head c-table__head--slim"><tr class="c-table__row">';
    for(let h in data.fields) {
        if(h == 'ID') {
            continue;
        }
        tbl += '<th class="c-table__cell c-table__cell--head">' + eval('data.fields.' + h) + '</th>';
    }
    tbl += '</tr></thead>';

    tbl += '<tbody>';
    for(let d in data.items) {
        tbl += '<tr class="c-table__row" onclick="showOrder(' + data.items[d].ID + ')">';
        for(let h in data.fields) {
            if(h == 'ID') {
                continue;
            }
            tbl += '<td class="c-table__cell">' + eval('data.items[d].' + h) + '</td>';
        }
        tbl += '</tr>';
    }
    tbl += '</tbody>';

    tbl += '</table></div></td></tr>';

    $(e).after(tbl);
}

function showOrder(id) {
    let data = sendAjax('m=orders&a=AjaxGetItem', {'itemId': id,}, null, false, true);
    if(!data.fields || !data.template_calc || !data.template_calc.data || !data.template_calc.result) {
        MessageBox('Произошла внутренняя ошибка!', true);
        return;
    }
    console.log(data);
    let _html = '<table width="100%"><tbody>';
    for(let f in data.fields) {
        let _tr = '<tr><td>' + eval('data.fields.' + f) + '</td>';
        let _sh = false;
        for (let d in data.template_calc.data) {
            _tr += '<td>';
            if(eval('data.template_calc.data[d].' + f)) {
                _sh = true;
                _tr += eval('data.template_calc.data[d].' + f);
            } else if(eval('data.template_calc.data[d].ResultCalc.' + f)) {
                _sh = true;
                _tr += eval('data.template_calc.data[d].ResultCalc.' + f);
            }
            _tr += '</td>';
        }
        _tr += '</tr>';
        if(_sh) {
            _html += _tr;
        }
    }
    _html += '</tbody></table>';
    CreateModalWindow2('order', _html);
}