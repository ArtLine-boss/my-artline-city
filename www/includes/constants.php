<?php
class CONSTANTS
{
    /** ДАТА */
    const DB_DATE_FORMAT = "Y-m-d";
    const DB_DATETIME_FORMAT = "Y-m-d H:i:s";
    const REPORT_DATE_FORMAT = "d.m.Y";
    const REPORT_DATETIME_FORMAT = "d.m.Y H:i:s";
    const REPORT_DATETIME_FORMAT_SHORT = "d.m.Y H:i";
    const NAME_DATE_FORMAT = "Y_m_d";
    const NAME_DATETIME_FORMAT = "Y_m_d_H_i_s";

    /** СТАТУС ЗАЯВКИ */
    const STATUS1 = 1; //цех
    const STATUS2 = 2; //готово
    const STATUS3 = 3; //отдано заказчику
    const STATUS4 = 4; //брак
    const STATUS10 = 10; //дизайн
    const STATUS11 = 11; //препресс
    const STATUS12 = 12; //печать
    const STATUS20 = 20; //возврат
    const STATUS21 = 21; //возврат

    /** ТИП ДАННЫХ КАЛЬКУЛЯТОРА */
    const TYPECALC = 1; //данные текущего пользователя
    const TYPENOTCALC = 2; //нераспределенные данные
    const TYPEALLCALC = 3; //все расчеты

    /** ТИП ОПЕРАЦИИ */
    const OPERATIONS_TYPE_PRINT = 1;
    const OPERATIONS_TYPE_CUT = 2;
    const OPERATIONS_TYPE_PRINT_COMBI_COLOR = 3;
    const OPERATIONS_TYPE_PLOTTER_CUT = 4;

    /** НАСТРОЙКИ */
    const SETTINGS22 = 22;
}

?>