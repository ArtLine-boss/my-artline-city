<?php


class core_ui extends core_Object
{
    var $login = null;
    var $user_name = null;
    var $password = null;
    var $last_time = 0;
    var $AccessArray = null;
    var $ajax_reload_url = null;

    public function __construct($scr = false, $ajax = false)
    {
        parent::__construct();
        if (!$scr) {
            session_start();
            $this->bind($_SESSION);
            if ($ajax) {
                $this->_checkAjax();
            } else {
                $this->_check();
            }
        }
    }

    private function _check()
    {
        $user = new classes_users();
        $where = array(
            'sql' => 'user_login=:user_login AND user_password=:user_password AND USER_STATUS=:USER_STATUS',
            'param' => array(
                'user_login' => $this->login,
                'user_password' => $this->password,
                'USER_STATUS' => 1
            )
        );
        $currentUser = null;
        if (empty(($currentUser = $user->loadAll($where, null, null, 1)[0]))) {
            session_destroy();
            $this->_redirect('?login=-1');
        }
        $this->user_name = $currentUser->USER_FIO;
        if ($currentUser->reset_password == 1) {
            header('Location: ../pages/rpassword.php?current&reset');
        }
        if ($this->last_time == 0 || (time() - floatval($this->last_time)) > 3600) {
            session_destroy();
            $this->_redirect('?login=-1');
        }

        $_SESSION["last_time"] = time();
        $this->last_time = time();
    }

    private function _checkAjax()
    {
        $user = new classes_users();
        $where = array(
            'sql' => 'user_login=:user_login AND user_password=:user_password AND USER_STATUS=:USER_STATUS',
            'param' => array(
                'user_login' => $this->login,
                'user_password' => $this->password,
                'USER_STATUS' => 1
            )
        );
        $currentUser = null;
        if (empty(($currentUser = $user->loadAll($where, null, null, 1)[0]))) {
            session_destroy();
            $this->ajax_reload_url = '?login=-1';
        }
        $this->user_name = $currentUser->USER_FIO;
        if ($currentUser->reset_password == 1) {
            $this->ajax_reload_url = 'Location: ../pages/rpassword.php?current&reset';
        }
        if ($this->last_time == 0 || (time() - floatval($this->last_time)) > 3600) {
            session_destroy();
            $this->ajax_reload_url = '?login=-1';
        }

        $_SESSION["last_time"] = time();
        $this->last_time = time();
    }

    public function _redirect($url = '')
    {
        header('Location: /www/index.php' . $url, true, 303);
        exit;
    }

    public function _redirect403()
    {
        if (headers_sent()) {
            die('</head><body><script type="text/javascript">window.location.href = "/www/403.html"</script></body>');
        } else {
            header('Location: /www/403.html', true, 403);
            exit;
        }
    }

    public function startViewPage()
    {
        include_once('startViewPage.php');
    }

    public function endViewPage()
    {
        include_once('endViewPage.php');
    }

    public function isAdmin()
    {
        $is_admin = false;
        do {
            $where = array(
                'sql' => 'user_login=:user_login AND user_password=:user_password AND USER_STATUS=:USER_STATUS',
                'param' => array(
                    'user_login' => $this->login,
                    'user_password' => $this->password,
                    'USER_STATUS' => 1
                )
            );
            $user = classes_users::all($where, null, null, 1);
            if (count($user) == 0) {
                break;
            }
            $user = $user[0];
            /** @var classes_users $user */
            if ($user->USER_PER == 4) {
                $is_admin = true;
            }
        } while (false);
        return $is_admin;
    }

    public function isAccess($access = null, $bool = false)
    {
        $acc = false;

        do {
            if (empty($access)) {
                if ($bool) {
                    break;
                } else {
                    $this->_redirect403();
                }
            }

            $AccessRole = $this->isAccessArray();

            if (count($AccessRole) === 0) {
                if ($bool) {
                    break;
                } else {
                    $this->_redirect403();
                }
            }

            if (!in_array($access, $AccessRole)) {
                if ($bool) {
                    break;
                } else {
                    $this->_redirect403();
                }
            }

            $acc = true;

        } while (false);

        return $acc;
    }

    public function isAccessArray()
    {
        $list = array();

        do {
            if (empty($this->login))
                break;
            if (!empty($this->AccessArray)) {
                $list = $this->AccessArray;
                break;
            }
            $AccessRole = new classes_AccessRole();
            $where = array(
                'sql' => "user_id=:user_id AND date_start <='" . API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT) . "' AND (date_end >='" . API::CurrentDate(DB_DATETIME_FORMAT) . "' OR date_end='0000-00-00 00:00:00')",
                'param' => array(
                    'user_id' => $this->login,
                ),
            );
            $access = $AccessRole->loadAll($where);
            foreach ($access as $k => $v) {
                $list[] = $v->level_id;
            }
            $this->AccessArray = $list;

        } while (false);

        return $list;
    }
}

?>