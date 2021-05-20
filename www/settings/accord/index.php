<?php
    $user = new classes_users();
    $users = $user->loadAll(['sql' => '`USER_STATUS` = 1 AND `USER_PER` IN (3, 4)']);

    $accordAM = classes_accordUsers::loadAllByAM();
    $accordMA = classes_accordUsers::loadAllByMA();
?>
<script>
    let ALL_USERS = [];
    <?php
            foreach ($users as $user) {
    ?>
    ALL_USERS.push({
        'id': '<? echo $user->getId(); ?>',
        'name': '<? echo $user->USER_FIO; ?>',
    });
    <?php
            }
    ?>
</script>
<div class="row">
    <div class="col-3 u-mb-medium">
        <a class="c-btn c-btn--success c-btn--fullwidth" onclick="addAccord()">Добавить соответствие</a>
    </div>
</div>

<?php
    viewTables($accordAM, 'Соответствие АртЛайн - Мечта', 'tableAccordAM');
    viewTables($accordMA, 'Соответствие Мечта - АртЛайн', 'tableAccordMA');

    function viewTables($data, $title, $id)
    {
        $table1 = '<div style="padding-bottom: 50px;"><h1>' . $title . '</h1>';
        $table1 .= '<table id="' . $id . '" class="c-table">';
        $table1 .= '<thead class="c-table__head"><tr class="c-table__row">';
        $table1 .= '<th class="c-table__cell c-table__cell--head">Пользователь 1</th>';
        $table1 .= '<th class="c-table__cell c-table__cell--head">Пользователь 2</th>';
        $table1 .= '<th class="c-table__cell c-table__cell--head">Дата добавления</th>';
        $table1 .= '<th class="c-table__cell c-table__cell--head">Кто добавил</th>';
        $table1 .= '<th class="c-table__cell c-table__cell--head no-sort"></th>';
        $table1 .= '</tr></thead>';

        $table1 .= '<tbody>';
        foreach ($data as $accord) {
            /** @var classes_accordUsers $accord */
            $table1 .= '<tr class="c-table__row">';
            $table1 .= '<td class="c-table__cell">' . $accord->getUser1Object()->USER_FIO . '</td>';
            $table1 .= '<td class="c-table__cell">' . $accord->getUser2Object()->USER_FIO . '</td>';
            $table1 .= '<td class="c-table__cell">' . API::FormatDate($accord->date_start, CONSTANTS::REPORT_DATETIME_FORMAT) . '</td>';
            $table1 .= '<td class="c-table__cell">' . $accord->getUserObject()->USER_FIO . '</td>';
            $table1 .= '<td class="c-table__cell"><i class="fa fa-trash fa-lg" style="cursor: pointer" onclick="cancelAccord(' . $accord->getId() . ')"></i></td>';
            $table1 .= '</tr>';
        }
        $table1 .= '</tbody></table></div>';

        echo $table1;
    }
?>

