<?php
// Получение списка дизайнеров
$listUserDesign = classes_users::getListDesign();
$selectStr = "";
foreach ($listUserDesign as $user) {
    $selectStr .= '<option value="' . $user->USER_LOGIN . '">' . $user->USER_FIO . '</option>';
}
?>
<div class="report_filter">
    <label><b>Дата С:</b></label>&nbsp;<input type="date" id="date_from" name="date_from">
    <label><b>Дата По:</b></label>&nbsp;<input type="date" id="date_to" name="date_to">
    <label><b>Дизайнер:</b></label>&nbsp;<select id="user_design" name="user_design" style="width: 200px">
        <option value="" selected>Все</option>
        <?php echo $selectStr; ?>
    </select>
    <label><a class="c-btn c-btn--success c-btn--fullwidth" style="display: inline"
              onclick="load()">Обновить</a></label>
</div>
<table id="report_design" class="c-table">
    <thead class="c-table__head">
    <tr class="c-table__row">
        <th class="c-table__cell c-table__cell--head">Номер заказа</th>
        <th class="c-table__cell c-table__cell--head">Дизайнер</th>
        <th class="c-table__cell c-table__cell--head">Сумма заказа</th>
        <th class="c-table__cell c-table__cell--head">Оплачено в отчетном периоде</th>
        <th class="c-table__cell c-table__cell--head">Оплачено после отчетного периода</th>
        <th class="c-table__cell c-table__cell--head">Всего оплачено</th>
        <th class="c-table__cell c-table__cell--head">Сумма продуктов, в которых есть работа дизайнеров</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
