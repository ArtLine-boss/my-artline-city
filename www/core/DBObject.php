<?php
    class core_DBObject extends core_Object {
        private $_tbl = null;
        private $_key = null;
        private $_class = null;
        private $pdo;

        public function __construct($table, $key, $cl = null)
        {
            parent::__construct();
            $this->_tbl = $table;
            $this->_key = $key;
            if(!empty($key))
                $this->$key = null;
            $this->_class = $cl;
        }

        //сеттер для ИД
        public function setId($id) {
            $key = $this->_key;
            $this->$key = $id;
        }

        //геттер для ИД
        public function getId() {
            $key = $this->_key;
            return $this->$key;
        }

        public function getClass() {
            return $this->_class;
        }

        public function getKey() {
            return $this->_key;
        }

        public function getTable() {
            return $this->_tbl;
        }

        //создание соединения
        private function mysql_connect() {
            $dsn = "mysql:host=" . CONFIG::HOST_DB . ";dbname=" . CONFIG::NAME_DB . ";charset=" . CONFIG::CHARSET;
            $connopt = array(
                PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            $this->pdo = new PDO($dsn, CONFIG::USER_DB, CONFIG::USER_PASSWORD, $connopt);
        }

        //проверка соединения
        private function mysql_get_status() {
            if(is_null($this->pdo)) {
                return false;
            } elseif ($this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) === CONFIG::HOST_DB . " via TCP/IP") {
                return true;
            } else {
                return false;
            }
        }

        //выполнение запроса
        private function mysql_query($query, $placeholders = null, $select = true) {
            $this->mysql_connect();
            if($this->mysql_get_status()) {
                //отключаем эмуляцию
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
                $this->pdo->beginTransaction();
                $stmt = $this->pdo->prepare($query);
                if(!is_null($placeholders)) {
                    $stmt->execute($placeholders);
                } else {
                    $stmt->execute();
                }
                if ($select) {
                    $arr = $stmt->fetchAll(PDO::FETCH_PROPS_LATE);
                    $this->pdo->commit();
                    $this->mysql_destroy();
                    return $arr;
                }
                else {
                    $id = $this->pdo->lastInsertId();
                    $this->pdo->commit();
                    $this->mysql_destroy();
                    if($id == 0)
                        return true;
                    return $id;
                }
            } else {
                $this->pdo->commit();
                $this->mysql_destroy();
                return false;
            }
        }

        /**
         * Выполнение запроса(ов)
         * @param string $sql
         * @return int
         */
        private function mysql_exec($sql) {
            try {
                $this->mysql_connect();
                if ($this->mysql_get_status()) {
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
                    $count = $this->pdo->exec($sql);
                    $this->mysql_destroy();
                    return $count === false ? -1 : $count;
                }
            } catch (PDOException $e) {
                $log = new core_log('logs/pdo_exec.log');
                $log->store($e->getCode() . ": " . $e->getMessage() . ". Строка: " . $e->getLine());
            }
            return -1;
        }

        //деструктор
        private function mysql_destroy() {
            $this->pdo = null;
        }

        //загрузка по ИД
        public function loadById($id) {
            $msg = null;

            do {
                $key_id = $this->_key;
                if(!empty($id)) {
                    $this->$key_id = $id;
                }
                if(empty($this->$key_id)) {
                    $msg = 'core_DBObject: Не задан идентификатор';
                    break;
                }

                $sql = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $this->_key . '=:' . $this->_key;
                $w = array($this->_key => $this->$key_id);
                $request = $this->mysql_query($sql, $w, true);
                if($request === false) {
                    $msg = 'core_DBObject: Не удалось создать соединение';
                    break;
                }
                if(empty($request)) {
                    $msg = 'core_DBObject: Запись не найдена';
                    break;
                }
                $this->bind($request[0]);

            } while(false);

            return $msg;
        }

        /*
         * загрузка по уникальному полю
         * $field - наименование поля, $value - значение, $isempty - может быть пустым
         */
        public function loadByUnique($field = null, $value = null, $isempty = false) {
            $msg = null;

            do {
                if(empty($field)) {
                    $msg = 'core_DBObject: Не задано уникальное поле';
                    break;
                }
                $sql = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $field . '=:' . $field . ' ORDER BY ' . $this->_key . ' DESC LIMIT 1';
                $w = array($field => $value);
                $request = $this->mysql_query($sql, $w, true);
                if($request === false) {
                    $msg = 'core_DBObject: Не удалось создать соединение';
                    break;
                }
                if(empty($request) && !$isempty) {
                    $msg = 'core_DBObject: Запись не найдена';
                    break;
                }
                $this->bind($request[0]);
            } while(false);

            return $msg;
        }

        /**
         * загрузка полного списка
         * $where имеет два поля: sql - запрос вида "key1=:key1,key2=:key2,..."; param - объект со значениями ключей
         * $field - поле по которому сортировать (по умолчанию ИД)
         * $desk - тип сортировки (0 - по возрастанию, 1 - по убыванию)
         * $limit - количество выбираемых полей
         */
        public function loadAll($where = null, $field = null, $desk = 0, $limit = null) {
            $list = array();
            do {
                $_WHERE = '';
                if(!empty($where) && is_array($where) && isset($where['sql'])) {
                    $_WHERE = ' WHERE ' . $where['sql'];
                    if(isset($where['param']))
                        $w = $where['param'];
                }
                if (empty($field) || !property_exists($this, $field))
                    $field = $this->_key;
                $_DESC = ' ASC';
                if ($desk != 0) {
                    $_DESC = ' DESC';
                }
                $_LIMIT = '';
                if (!empty($limit)) {
                    $_LIMIT = ' LIMIT ' . intval($limit);
                }
                $sql = 'SELECT * FROM ' . $this->_tbl . $_WHERE . ' ORDER BY ' . $field . $_DESC . $_LIMIT;
                $request = $this->mysql_query($sql, $w, true);
                if($request === false) {
                    $msg = 'core_DBObject: Не удалось создать соединение';
                    break;
                }
                foreach ($request as $k => $v) {
                    $class = 'classes_' . $this->_tbl;
                    if(!empty($this->_class)) {
                        $class = $this->_class;
                    }
                    $e = new $class();
                    if(null !== ($msg = $e->bind($v))) {
                        $list = array();
                        break;
                    }
                    $list[] = $e;
                }
            } while(false);

            return $list;
        }

        //выполнить запрос и получить массив текущих объектов
        public function selectByQuery($query = null, $placeholders = null) {
            $list = array();

            do {
                if(empty($query))
                    break;

                $request = $this->mysql_query($query, $placeholders, true);
                if($request === false) {
                    break;
                }
                foreach ($request as $k => $v) {
                    $class = 'classes_' . $this->_tbl;
                    if(!empty($this->_class)) {
                        $class = $this->_class;
                    }
                    $e = new $class();
                    if(null !== ($msg = $e->bind($v))) {
                        $list = array();
                        break;
                    }
                    $list[] = $e;
                }

            } while(false);

            return $list;
        }

        //произвольный запрос
        public function select($query, $placeholders = null, $select = true) {
            return $this->mysql_query($query, $placeholders, $select);
        }

        public static function s_select($query, $placeholders = null, $select = true) {
            $class = new static('', '');
            return $class->select($query, $placeholders, $select);
        }

        //запись или обновление
        public function store() {
            $msg = null;

            do {
                $k = $this->_key;
                if(empty($this->$k)) {
                    $msg = $this->insert();
                } else {
                    $msg = $this->update();
                }
            } while(false);

            return $msg;
        }

        //запись
        private function insert() {
            $msg = null;
            $_notfield = ['pdo', '_tbl', '_key', $this->_key, '_class'];
            do {
                $field = '';
                $val = '';
                $w = array();
                foreach ($this as $k => $v) {
                    if(!in_array($k, $_notfield) && !empty($v)) {
                        $field .= $k . ',';
                        $val .= ':' . $k . ',';
                        $w[$k] = $v;
                    }
                }
                if(empty($val)) {
                    $msg = 'core_DBObject: Нет полей для записи';
                    break;
                }
                $field = trim($field, ',');
                $val = trim($val, ',');
                $sql = 'INSERT INTO ' . $this->_tbl . ' (' . $field . ') VALUES (' . $val . ')';
                $request = $this->mysql_query($sql, $w, false);
                if(empty($request)) {
                    $msg = 'core_DBObject: Не удалось записать запись';
                    break;
                }
                $msg = $this->loadById($request);
            } while(false);

            return $msg;
        }

        //обновление
        private function update() {
            $msg = null;
            $_notfield = ['pdo', '_tbl', '_key', $this->_key, '_class'];
            do {
                $w = array();
                $val = '';
                foreach ($this as $k => $v) {
                    if(!in_array($k, $_notfield) && !empty($v)) {
                        $val .= $k . '=:' . $k . ',';
                        $w[$k] = $v;
                    }
                }
                if(empty($val)) {
                    $msg = 'core_DBObject: Нет полей для обновления';
                    break;
                }
                $val = trim($val, ',');
                $key = $this->_key;
                $w[$key] = $this->$key;
                $sql = 'UPDATE ' . $this->_tbl . ' SET ' . $val . ' WHERE ' . $key . '=:' . $key;
                $request = $this->mysql_query($sql, $w, false);
                if(empty($request)) {
                    $msg = 'core_DBObject: Не удалось обновить запись';
                    break;
                }
                $msg = $this->loadById($this->getId());
            } while(false);

            return $msg;
        }

        //удаление
        public function delete() {
            $msg = null;

            do {
                $id = $this->_key;
                if(empty($this->_key) || empty($this->$id)) {
                    $msg = 'Объект не инициализирован';
                    break;
                }

                $sql = 'DELETE FROM ' . $this->_tbl . ' WHERE ' . $this->_key . '=:id';
                $w = array(
                    'id' => $this->$id
                );
                $request = $this->mysql_query($sql, $w, false);
                if(empty($request)) {
                    $msg = 'core_DBObject: Не удалось удалить запись';
                    break;
                }

            } while(false);

            return $msg;
        }

        //определяем или инициализировался объект
        public function getInit() {
            $id = $this->_key;
            return (empty($this->_key) || empty($this->$id)) ? false : true;
        }

        //сброс значения ключа (для копирования данных)
        public function resetId() {
            $k = $this->_key;
            $this->$k = null;
        }

        /** Фабрика загрузки объекта по ид */
        public function _oid($oid) {
            do {
                $this->loadById($oid);
            } while(false);

            return $this;
        }

        /**
         * Поиск по ИД (статический метод)
         * @param $id
         * @return static
         */
        public static function oid($id) {
            $class = core_Cache::getInstance()->get(get_class(new static()), $id);
            if($class === null) {
                $class = new static();
                $class = $class->_oid($id);
                core_Cache::getInstance()->set($class);
            }
            return $class;
        }

        /**
         * загрузка полного списка (статический метод)
         * @param null $where
         * @param null $field
         * @param int $desk
         * @param null $limit
         * @return array
         */
        public static function all($where = null, $field = null, $desk = 0, $limit = null) {
            $class = new static();
            $list = $class->loadAll($where, $field, $desk, $limit);
            return $list;
        }

        /**
         * Загрузка по уникальному полю
         * @param string|null $field Поле
         * @param string|null $value Значение
         * @param bool $isempty
         * @return static
         */
        public static function unique($field = null, $value = null, $isempty = true) {
            $class = new static();
            $msg = $class->loadByUnique($field, $value, true);
            if($msg !== null) {
                $class = new static();
            }
            return $class;
        }

        /**
         * Выполнение запроса(ов)
         * @param string $sql
         * @return mixed
         */
        public function exec($sql) {
            return $this->mysql_exec($sql);
        }
    }
?>