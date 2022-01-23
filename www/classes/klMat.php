<?php

class classes_klMat extends core_DBObject
{
    var $parent = null;
    var $title = null;
    var $flags = null;

    public function __construct()
    {
        parent::__construct('kl_mat', 'id', 'classes_klMat');
    }

    public function selectByQuery($query = null, $placeholders = null)
    {
        $mats = array();

        do {

            $mats = parent::selectByQuery($query, $placeholders);

            $template_parent = array();
            foreach ($mats as $k => $v) {
                $_parent = $v->parent;
                if (array_key_exists($v->getId(), $template_parent)) {
                    $v->parent = $template_parent[$v->getId()]['parent'];
                    $v->title_parent = $template_parent[$v->getId()]['title_parent'];
                } else if (array_key_exists($v->parent, $template_parent)) {
                    $v->parent = $template_parent[$v->parent]['parent'];
                    $v->title_parent = $template_parent[$v->parent]['title_parent'];
                    $v->title = $template_parent[$v->parent]['title_parent'] . ' ' . $v->title;
                } else {
                    $this->tree($v);
                    $template_parent[$_parent] = array(
                        'parent' => $v->parent,
                        'title_parent' => $v->title_parent
                    );
                }
            }


        } while (false);

        return $mats;
    }

    protected function tree(&$kl_mat = null)
    {
        do {
            if (empty($kl_mat))
                break;
            if (get_class($kl_mat) != $this->getClass())
                break;
            if ($kl_mat->flags == 1)
                break;
            $tmp = new classes_klMat();
            if (null != ($msg = $tmp->loadById($kl_mat->parent)))
                break;
            if ($tmp->flags == 0) {
                self::tree($tmp);
            }
            $kl_mat->parent = $tmp->getId();
            $kl_mat->title = $tmp->title . ' ' . $kl_mat->title;
            $kl_mat->title_parent = $tmp->title;

        } while (false);
    }

    public function get_tree()
    {
        $this->tree($this);
    }

    public function fun_group()
    {
        $name = null;

        do {
            if (empty($this->id))
                break;

            $name = $this->title;
            $id_pr = $this->parent;
            $flags = $this->flags;

            while ($flags == "0") {
                $kl_mat = new classes_klMat();
                if (null !== ($msg = $kl_mat->loadById($id_pr)))
                    continue;
                $names = $kl_mat->title;
                $name = $names;
                $flags = $kl_mat->flags;
                $id_new = $kl_mat->parent;
                $id_pr = $id_new;
            }

        } while (false);

        return $name;
    }

    public function fun_names()
    {
        $name = null;

        do {
            if (empty($this->id))
                break;

            $name = $this->title;
            $id_pr = $this->parent;
            $flags = $this->flags;
            while ($flags == "0") {
                $kl_mat = new classes_klMat();
                if (null !== ($msg = $kl_mat->loadById($id_pr)))
                    continue;
                $names = $kl_mat->title;
                $name = $names . " " . $name;
                $flags = $kl_mat->flags;
                $id_new = $kl_mat->parent;
                $id_pr = $id_new;
            }

        } while (false);

        return $name;
    }

    //определяем или самоклейка
    public function getSelfAdhesive()
    {
        $result = false;

        do {
            $parent = $this->parent;
            while (!empty($parent)) {
                $klMat = new classes_klMat();
                if (null !== ($msg = $klMat->loadById($parent)))
                    break;
                $parent = $klMat->parent;
                $title = $klMat->title;
                $flag = $klMat->flags;
                if (stristr($title, 'самоклеящаяся') && $flag == 1) {
                    $result = true;
                    break;
                }
            }
        } while (false);

        return $result;
    }

    /**
     * Проверка парентов
     * @return array
     */
    public static function getInvalidRow()
    {
        $result = [];
        $list = static::all(['sql' => 'parent > 0']);
        foreach ($list as $tree) {
            /** @var static $tree */
            $parentTree = static::oid($tree->parent);
            if (!$parentTree->getInit()) {
                $result[] = $tree;
            }
        }
        return $result;
    }
}

?>
