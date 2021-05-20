<?php
class ACCESSES
{
    //Печать товарного чека
    const PRINT_ORDERS_CHECK = 1;
    //Доступ к странице расчетов
    const ACCESS_PAGE_CALC = 2;
    //Доступ к нераспределенным заявкам
    const ACCESS_PAGE_CALC_NOT_USER = 3;
    //Доступ ко всем расчетам
    const ACCESS_PAGE_CAL_ALL = 4;
    //Доступ к странице заказов
    const ACCESS_PAGE_ORDERS = 5;
    //Доступ к странице клиентов
    const ACCESS_PAGE_CLIENTS = 6;
    ///Доступ к странице ТН
    const ACCESS_PAGE_TN = 7;
    //Доступ к странице оплат
    const ACCESS_PAGE_PAYMENTS = 8;
    //Доступ к странице подготовки файлов
    const ACCESS_PAGE_TASKS = 9;
    //Доступ к странице склада
    const ACCESS_PAGE_STOCK = 10;
    //Доступ к странице загруженности производства
    const ACCESS_PAGE_WORKLOAD = 11;
    //Доступ к странице производства
    const ACCESS_PAGE_WORKS = 12;
    //Доступ к отчетам
    const ACCESS_PAGE_REPORTS = 13;
    //Доступ к нераспределенным контактам из фреша
    const ACCESS_PAGE_REPORTS_CONTACTS_FRESH = 14;
    //Доступ к отчету по артлайнерам
    const ACCESS_PAGE_REPORTS_ARTLINER = 15;
    //Доступ к отчету по оформленным заказам
    const ACCESS_PAGE_REPORTS_ORDERS = 16;
    //Доступ к отчету по дизайнерам
    const ACCESS_PAGE_REPORTS_DESIGNS = 17;
    //Доступ к отчету по поставщикам
    const ACCESS_PAGE_REPORTS_PROVIDERS = 18;
    //Доступ к отчету по клиентам с возможностью сохранения в Excel
    const ACCESS_PAGE_REPORTS_ORDERS_TO_XLS = 19;
    //Доступ к странице настроек
    const ACCESS_PAGE_SETTINGS = 20;
    //Доступ к классификатору склада
    const ACCESS_PAGE_SETTINGS_TREE = 21;
    //Доступ к настройке продуктов
    const ACCESS_PAGE_SETTINGS_PRODUCTS = 22;
    //Доступ к настройке кодов продуктов
    const ACCESS_PAGE_SETTINGS_CODE_PRODUCTS = 23;
    //Доступ к настройке оборудования
    const ACCESS_PAGE_SETTINGS_EQUIPMENTS = 24;
    //Доступ к настройке операций
    const ACCESS_PAGE_SETTINGS_OPERATIONS = 25;
    //Доступ к календарю
    const ACCESS_PAGE_SETTINGS_CALENDAR = 26;
    //Доступ к сбросу паролей пользователей
    const ACCESS_PAGE_SETTINGS_RESET_PASSWORD = 27;
    //Доступ к справочникам
    const ACCESS_PAGE_DIRECTORY = 28;
    //Доступ к справочнику пользователей
    const ACCESS_PAGE_DIRECTORY_USERS = 29;
    //Доступ к справочнику материалов
    const ACCESS_PAGE_DIRECTORY_MATERIALS = 30;
    //Доступ к справочнику штампов
    const ACCESS_PAGE_DIRECTORY_STAMPS = 31;
    //Доступ к справочнику параметров
    const ACCESS_PAGE_DIRECTORY_PARAMETRS = 32;
    //Доступ к справочнику дизайна
    const ACCESS_PAGE_DIRECTORY_DESIGNS = 33;
    //Доступ к справочнику единиц измерения
    const ACCESS_PAGE_DIRECTORY_UNITS = 34;
    // Доступ к странице соответствий пользователей в компаниях
    const ACCESS_PAGE_SETTINGS_ACCORD = 35;
    //Печать бланка заказа
    const PRINT_ORDERS_BLANK = 36;
    //Доступ к странице заказов в работе
    const ACCESS_PAGE_ORDERS_ACTIVE = 37;
    //Доступ к странице отчета по менеджерам
    const ACCESS_PAGE_ORDERS_REPORT_MANAGER = 38;
    //Доступ к странице оплаченных заказов но не в работе
    const ACCESS_PAGE_ORDERS_NOTWORK = 39;
    //Доступ к странице заказов (продукты)
    const ACCESS_PAGE_ORDERS_PRODUCTS = 40;
    //Доступ к странице заказов (оплаты)
    const ACCESS_PAGE_ORDERS_PAYMENTS = 41;
    //Доступ к странице заказов (ТН)
    const ACCESS_PAGE_ORDERS_TN = 42;
    //Доступ к странице заказов (Доставка)
    const ACCESS_PAGE_ORDERS_POST = 43;
}

?>