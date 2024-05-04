$(document).ready(function () {
    // Инициализация таблицы
    $("#report_design").DataTable(getOptTable());

    // Заполняем даты текущим месяцем
    let date = new Date(), y = date.getFullYear(), m = date.getMonth();
    let firstDay = new Date(y, m, 1)
        .toLocaleString('ru-RU', {year: 'numeric', month: 'numeric', day: 'numeric'})
        .split(".").reverse().join("-");
    let lastDay = new Date(y, m + 1, 0).toLocaleString('ru-RU', {year: 'numeric', month: 'numeric', day: 'numeric'})
        .split(".").reverse().join("-");

    $('#date_from').val(firstDay).change();
    $('#date_to').val(lastDay).change();
    load();
});

/**
 * Загрузка отчета
 */
function load() {
    $('#report_design').DataTable().destroy();
    clearTable(document.getElementById('report_design'));
    sendAjax(
        'm=reports&u=designNoPayment&a=AjaxListAll',
        {
            date_from: $('#date_from').val(),
            date_to: $('#date_to').val(),
            username: $('#user_design option:selected').val()
        },
        'viewData'
    );
}

/**
 * Отрисовка отчета
 * @param data
 */
function viewData(data) {
    if (data) {
        for (let i in data) {
            let html = '<tr class="c-table__row">';
            html += '<td class="c-table__cell report-table-order">' + data[i].ORDER_ID + '</td>';
            html += '<td class="c-table__cell report-table-username">' + data[i].username + '</td>';
            html += '<td class="c-table__cell">' + data[i].sum_all + '</td>';
            html += '<td class="c-table__cell">' + data[i].sum_oplati_before + '</td>';
            html += '<td class="c-table__cell">' + data[i].sum_oplati_after + '</td>';
            html += '<td class="c-table__cell">' + data[i].sum_oplati_all + '</td>';
            html += '<td class="c-table__cell">' + data[i].sum_design + '</td>';
            html += '</tr>';

            $('#report_design tbody').append(html);
        }
    }

    $('#report_design').DataTable(getOptTable()).draw();
}

/**
 * Параметры таблицы
 * @returns {{language: {url: string}, columnDefs: [{sorting: boolean, searching: boolean, targets: string}], order: (number|string)[][]}}
 */
function getOptTable() {
    return {
        "language": {
            "sProcessing": "Подождите...",
            "sLengthMenu": "Показать _MENU_ записей",
            "sZeroRecords": "Записи отсутствуют.",
            "sInfo": "Записи с _START_ до _END_ из _TOTAL_ записей",
            "sInfoEmpty": "Записи с 0 до 0 из 0 записей",
            "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
            "sInfoPostFix": "",
            "sSearch": "Поиск:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Первая",
                "sPrevious": "Предыдущая",
                "sNext": "Следующая",
                "sLast": "Последняя"
            },
            "oAria": {
                "sSortAscending": ": активировать для сортировки столбца по возрастанию",
                "sSortDescending": ": активировать для сортировки столбцов по убыванию"
            }
        },
        "order": [[0, 'asc']],
        'columnDefs': [{
            'targets': "no-sort",
            'sorting': false,
            'searching': false,
        }],
    };
}