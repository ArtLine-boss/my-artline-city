<?php
    $ajaxObject = new core_Dto();
    do {
        // Админ
        $user = new classes_users();
        if(null !== $user->LoadByLogin($_SESSION['login'])) {
            $ajaxObject->setMsg('Произошла непредвидимая ошибка. Обратитесь к системному администратору');
            break;
        }

        // Пользователь 1й компании
        $user1_id = isset($_POST['user1']) && intval($_POST['user1']) > 0 ? intval($_POST['user1']) : 0;
        if($user1_id === 0) {
            $ajaxObject->setMsg('Не задан пользователь 1й компании');
            break;
        }
        $user1 = new classes_users();
        if(null !== ($msg = $user1->loadById($user1_id))) {
            $ajaxObject->setMsg('Ошибка добавления пользователя 1й компании. ' . $msg);
            break;
        }

        // Пользователь 2й компании
        $user2_id = isset($_POST['user2']) && intval($_POST['user2']) > 0 ? intval($_POST['user2']) : 0;
        if($user2_id === 0) {
            $ajaxObject->setMsg('Не задан пользователь 2й компании');
            break;
        }
        $user2 = new classes_users();
        if(null !== ($msg = $user2->loadById($user2_id))) {
            $ajaxObject->setMsg('Ошибка добавления пользователя 1й компании. ' . $msg);
            break;
        }

        if($user1_id == $user2_id) {
            $ajaxObject->setMsg('Добавление соответствия тому же пользователю!');
            break;
        }

        // Собираем объект
        $accord = new classes_accordUsers();
        $accord->user = $user->USER_LOGIN;
        $accord->user1 = $user1->USER_LOGIN;
        $accord->user2 = $user2->USER_LOGIN;
        $accord->accord_type = intval($_POST['accordType']);
        $accord->date_start = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
        if(null !== ($msg = $accord->store())) {
            $ajaxObject->setMsg('Ошибка сохранения соответствия. ' . $msg);
            break;
        }
    } while(false);
?>