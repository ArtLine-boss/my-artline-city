<?php

    /*
     * Пишем права
     * ссылка - http://artline.city/www/index.php?m=scripts&u=addLevelForUsers&scr=1
     * */
    $users = new classes_users();
    $list = $users->loadAll();

    /** массивы доступов */
    $role1 = [];
    $role2 = [
        ACCESSES::ACCESS_PAGE_ORDERS,
        ACCESSES::ACCESS_PAGE_CLIENTS,
        ACCESSES::ACCESS_PAGE_REPORTS,
        ACCESSES::ACCESS_PAGE_REPORTS_CONTACTS_FRESH,
        ACCESSES::ACCESS_PAGE_TN,
        ACCESSES::ACCESS_PAGE_PAYMENTS,
        ACCESSES::ACCESS_PAGE_STOCK,
        ACCESSES::ACCESS_PAGE_REPORTS_ORDERS,
        ACCESSES::ACCESS_PAGE_WORKLOAD,
        ACCESSES::ACCESS_PAGE_ORDERS_ACTIVE,
        ACCESSES::ACCESS_PAGE_ORDERS_REPORT_MANAGER,
        ACCESSES::ACCESS_PAGE_ORDERS_NOTWORK,
        ACCESSES::ACCESS_PAGE_ORDERS_PRODUCTS,
        ACCESSES::ACCESS_PAGE_ORDERS_PAYMENTS,
        ACCESSES::ACCESS_PAGE_ORDERS_TN,
    ];
    $role3 = [
        ACCESSES::ACCESS_PAGE_REPORTS,
        ACCESSES::ACCESS_PAGE_ORDERS,
        ACCESSES::ACCESS_PAGE_CALC,
        ACCESSES::ACCESS_PAGE_CLIENTS,
        ACCESSES::ACCESS_PAGE_REPORTS_CONTACTS_FRESH,
        ACCESSES::ACCESS_PAGE_WORKLOAD,
        ACCESSES::ACCESS_PAGE_ORDERS_ACTIVE,
        ACCESSES::ACCESS_PAGE_ORDERS_REPORT_MANAGER,
        ACCESSES::ACCESS_PAGE_ORDERS_NOTWORK,
        ACCESSES::ACCESS_PAGE_ORDERS_PRODUCTS,
        ACCESSES::ACCESS_PAGE_ORDERS_PAYMENTS,
        ACCESSES::ACCESS_PAGE_ORDERS_TN,
    ];
    $role4 = [
        ACCESSES::ACCESS_PAGE_ORDERS,
        ACCESSES::ACCESS_PAGE_CALC,
        ACCESSES::ACCESS_PAGE_CLIENTS,
        ACCESSES::ACCESS_PAGE_TN,
        ACCESSES::ACCESS_PAGE_PAYMENTS,
        ACCESSES::ACCESS_PAGE_TASKS,
        ACCESSES::ACCESS_PAGE_STOCK,
        ACCESSES::ACCESS_PAGE_WORKLOAD,
        ACCESSES::ACCESS_PAGE_WORKS,
        ACCESSES::ACCESS_PAGE_REPORTS,
        ACCESSES::ACCESS_PAGE_REPORTS_CONTACTS_FRESH,
        ACCESSES::ACCESS_PAGE_REPORTS_ARTLINER,
        ACCESSES::ACCESS_PAGE_REPORTS_ORDERS,
        ACCESSES::ACCESS_PAGE_REPORTS_DESIGNS,
        ACCESSES::ACCESS_PAGE_REPORTS_PROVIDERS,
        ACCESSES::ACCESS_PAGE_REPORTS_ORDERS_TO_XLS,
        ACCESSES::ACCESS_PAGE_SETTINGS,
        ACCESSES::ACCESS_PAGE_SETTINGS_TREE,
        ACCESSES::ACCESS_PAGE_SETTINGS_PRODUCTS,
        ACCESSES::ACCESS_PAGE_SETTINGS_CODE_PRODUCTS,
        ACCESSES::ACCESS_PAGE_SETTINGS_EQUIPMENTS,
        ACCESSES::ACCESS_PAGE_SETTINGS_OPERATIONS,
        ACCESSES::ACCESS_PAGE_SETTINGS_CALENDAR,
        ACCESSES::ACCESS_PAGE_SETTINGS_RESET_PASSWORD,
        ACCESSES::ACCESS_PAGE_DIRECTORY,
        ACCESSES::ACCESS_PAGE_DIRECTORY_USERS,
        ACCESSES::ACCESS_PAGE_DIRECTORY_MATERIALS,
        ACCESSES::ACCESS_PAGE_DIRECTORY_STAMPS,
        ACCESSES::ACCESS_PAGE_DIRECTORY_PARAMETRS,
        ACCESSES::ACCESS_PAGE_DIRECTORY_DESIGNS,
        ACCESSES::ACCESS_PAGE_DIRECTORY_UNITS,
        ACCESSES::PRINT_ORDERS_CHECK,
        ACCESSES::ACCESS_PAGE_ORDERS_ACTIVE,
        ACCESSES::ACCESS_PAGE_ORDERS_REPORT_MANAGER,
        ACCESSES::ACCESS_PAGE_ORDERS_NOTWORK,
        ACCESSES::ACCESS_PAGE_ORDERS_PRODUCTS,
        ACCESSES::ACCESS_PAGE_ORDERS_PAYMENTS,
        ACCESSES::ACCESS_PAGE_ORDERS_TN,
        ACCESSES::ACCESS_PAGE_ORDERS_POST,
    ];
    $role5 = [
        ACCESSES::ACCESS_PAGE_TASKS,
    ];
    $role6 = [
        ACCESSES::ACCESS_PAGE_TASKS,
    ];
    $role7 = [
        ACCESSES::ACCESS_PAGE_TASKS,
    ];
    $role8 = [];
    $role9 = [
        ACCESSES::ACCESS_PAGE_STOCK,
        ACCESSES::ACCESS_PAGE_SETTINGS_TREE,
    ];

    /** проводим для категорий права */
    foreach ($list as $key => $value) {
        if($value->USER_STATUS != 1)
            continue;

        switch ($value->USER_PER) {
            case 1:
                foreach ($role1 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 2:
                foreach ($role2 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 3:
                foreach ($role3 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 4:
                foreach ($role4 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 5:
                foreach ($role5 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 6:
                foreach ($role6 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 7:
                foreach ($role7 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 8:
                foreach ($role8 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
            case 9:
                foreach ($role9 as $k => $v) {
                    add($v, $value->USER_LOGIN);
                }
                break;
        }
    }

    /** Проводим доп права для пользователя */
    $roleForUser = array(
        'admin' => [
            ACCESSES::PRINT_ORDERS_CHECK,
        ],
        '026' => [
            ACCESSES::ACCESS_PAGE_REPORTS,
            ACCESSES::ACCESS_PAGE_REPORTS_ARTLINER,
            ACCESSES::ACCESS_PAGE_TN,
            ACCESSES::ACCESS_PAGE_PAYMENTS,
            ACCESSES::ACCESS_PAGE_STOCK,
            ACCESSES::PRINT_ORDERS_CHECK,
            ACCESSES::ACCESS_PAGE_ORDERS_POST,
        ],
        '030' => [
            ACCESSES::ACCESS_PAGE_TN,
            ACCESSES::ACCESS_PAGE_PAYMENTS,
            ACCESSES::ACCESS_PAGE_STOCK,
            ACCESSES::PRINT_ORDERS_CHECK,
        ],
        /*'032' => [
            ACCESSES::ACCESS_PAGE_TN,
            ACCESSES::ACCESS_PAGE_PAYMENTS,
        ],
        '033' => [
            ACCESSES::ACCESS_PAGE_TN,
            ACCESSES::ACCESS_PAGE_PAYMENTS,
            ACCESSES::PRINT_ORDERS_CHECK,
        ],*/
        'buh2' => [
            ACCESSES::ACCESS_PAGE_REPORTS,
            ACCESSES::ACCESS_PAGE_REPORTS_ARTLINER,
        ],
        'preprint' => [
            ACCESSES::ACCESS_PAGE_DIRECTORY,
            ACCESSES::ACCESS_PAGE_DIRECTORY_STAMPS,
        ],
        '015' => [
            ACCESSES::ACCESS_PAGE_REPORTS,
            ACCESSES::ACCESS_PAGE_REPORTS_DESIGNS,
        ],
        '008' => [
            ACCESSES::ACCESS_PAGE_REPORTS,
            ACCESSES::ACCESS_PAGE_REPORTS_ORDERS,
        ],
        '029' => [
            ACCESSES::ACCESS_PAGE_REPORTS,
            ACCESSES::ACCESS_PAGE_REPORTS_ORDERS,
        ],
    );

    foreach ($roleForUser as $key => $value) {
        foreach ($value as $k => $v) {
            add($v, $key);
        }
    }

    API::vardump('ВЫПОЛНЕНО', false);

    function add($level = null, $login = null) {
        do {
            if(empty($level) || empty($login))
                break;

            $access = new classes_AccessRole();
            $where = array(
                'sql' => 'user_id=:LOGIN AND level_id=:LEVEL',
                'param' => array(
                    'LOGIN' => $login,
                    'LEVEL' => $level,
                )
            );
            $list = $access->loadAll($where);
            unset($access);
            if(count($list) > 0)
                break;

            $access = new classes_AccessRole();
            $access->level_id = $level;
            $access->user_id = $login;
            $access->date_start = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
            if(null !== ($msg = $access->store()))
                API::vardump($msg, false);
            unset($access);

        } while(false);
    }

?>