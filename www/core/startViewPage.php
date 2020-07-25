<?php
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Система управления заказами</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="css/img/favicon.png" type="image/png">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/controller.css">
    <?php
        if(is_array($_GET) && count($_GET) && isset($_GET['m'])) {
            $css = strval($_GET['m']);
            if(isset($_GET['u'])) {
                $css .= "_" . strval($_GET['u']);
            }
            if(isset($_GET['a'])) {
                $css .= "_" . strval($_GET['a']);
            }
            $css = 'css/' . $css . ".css";
            if(file_exists($css))
                echo '<link rel="stylesheet" href="' . $css . '">';
        }
    ?>
</head>
<body>
    <div class="o-page__sidebar js-page-sidebar">
        <div class="c-sidebar">
            <a class="c-sidebar__brand" href="/pages/orders.php">
                <img class="c-sidebar__brand-img" src="css/img/logo-white.png" alt="Logo"> АртЛайнСити
            </a>
            <ul class="c-sidebar__list">
            <?php
                $menu = new classes_AccessMenu();
                $menuArray = $menu->loadAll(array('sql' => 'parent_id=0', 'param' => null));
                $id_ul = 0;

                $content_menu = "";
                foreach ($menuArray as $k => $v) {
                    if(!empty($v->accessValue)) {
                        $access = null;
                        eval('$access = ACCESSES::' . $v->accessValue . ';');
                        if (!$this->isAccess($access, true))
                            continue;
                    }

                    if(empty($v->url)) {
                        $id_ul++;

                        $menu2 = new classes_AccessMenu();
                        $menuArray2 = $menu2->loadAll(array('sql' => 'parent_id=' . $v->ID, 'param' => null));
                        $id_ul2 = 0;
                        $content_menu2 = "";
                        $is_open = false;
                        foreach ($menuArray2 as $k2 => $v2) {
                            if(!empty($v2->accessValue)) {
                                $access2 = null;
                                eval('$access2 = ACCESSES::' . $v2->accessValue . ';');
                                if (!$this->isAccess($access2, true))
                                    continue;
                            }
                            $is_active = '';
                            if(strpos($_SERVER['REQUEST_URI'], $v2->url) !== false || $_SERVER['REQUEST_URI'] == $v2->url) {
                                $is_active = ' is-active';
                                $is_open = true;
                            }
                            $content_menu2 .= '<li><a class="c-sidebar__link' . $is_active . '" href="' . $v2->url . '"><i class="' . $v2->icon . '"></i>' . $v2->name . '</a></li>';
                        }
                        $content_menu .= '<li class="c-sidebar__item has-submenu' . ($is_open ? ' is-open' : '') . '">';
                        $content_menu .= '<a class="c-sidebar__link" data-toggle="collapse" href="#submenu' . $id_ul . '" aria-expanded="' . (!empty($is_active) ? 'true' : 'false') . '" aria-controls="submenu' . $id_ul . '">
                                <i class="' . $v->icon . '"></i>' . $v->name . '
                            </a>';
                        $content_menu .= '<ul class="c-sidebar__submenu collapse' . ($is_open ? ' show' : '') . '" id="submenu' . $id_ul . '">';
                        $content_menu .= $content_menu2;
                        $content_menu .= '</ul>';

                        continue;
                    }
                    $is_active = '';
                    if(strpos($_SERVER['REQUEST_URI'], $v->url) !== false || $_SERVER['REQUEST_URI'] == $v->url)
                        $is_active = ' is-active';
                    $content_menu .= '<li class="c-sidebar__item">
                            <a class="c-sidebar__link' . $is_active . '" href="' . $v->url . '">
                                <i class="' . $v->icon . '"></i>' . $v->name . '
                            </a>
                        </li>';
                }
                echo $content_menu;
            ?>
                <li class="c-sidebar__item has-submenu">
                    <a class="c-sidebar__link" data-toggle="collapse" href="#submenuUser" aria-expanded="false" aria-controls="submenuUser">
                        <i class="fa fa-user-circle fa-2x"></i>&nbsp;<?php echo $this->user_name; ?>
                    </a>
                </li>
                <ul class="c-sidebar__submenu collapse" id="submenuUser">
                    <li><a class="c-sidebar__link" href="#" onclick="ComboUser()"><i class="fa fa-user-plus"></i>&nbsp;Совмещенный пользователь</a></li>
                    <li><a class="c-sidebar__link" href="/pages/rpassword.php?current"><i class="fa fa-unlock"></i>&nbsp;Сбросить пароль</a></li>
                    <li><a class="c-sidebar__link" href="/pages/exit.php"><i class="fa fa-sign-out"></i>&nbsp;Выход</a></li>
                </ul>
            </ul>
        </div>
    </div>
    <main class="o-page__content">
        <header class="c-navbar u-mb-medium">
            <button class="c-sidebar-toggle u-mr-small">
                <a class="c-sidebar__brand small_log">
                    <img class="c-sidebar__brand-img" src="css/img/logo-white.png" alt="Logo"> АртЛайнСити
                </a>
            </button>
        </header>
        <div class="container-fluid">



