<?php
class popup_Mail extends popup_Abstract {
    protected function getParametrs()
    {
        $params = null;

        do {
            $object = new entity_mail();
            $whr = [
                'sql' => "`mail`.`track_cod` <> '' AND `mail`.`date_otpr` IS NOT NULL AND `mail`.`date` IS NOT NULL AND `mail`.`status_read` = 0 AND `mail`.`user_login` = '" . $_SESSION['login'] . "'",
            ];
            $params = $object->loadAll($whr);
        } while(false);

        return $params;
    }
    protected function getEcho($param = null)
    {
        $code = '';

        do {
            if(empty($param) || !is_array($param) || count($param) == 0) {
                break;
            }

            $list = '';
            $ids = [];
            foreach ($param as $k => $ml) {
                if(!is_a($ml, 'entity_mail')) {
                    continue;
                }
                $list .= ((!empty($list)) ? '\r\n' : '') . 'Заказ ' . $ml->id_ord . ' (трек ' . $ml->track_cod . ') доставлен';
                $ids[] = $ml->getId();
            }

            if(empty($list)) {
                break;
            }

            $code = '<script>
                window.onload = function() {
                    if(confirm("' . $list . '")) {
                        $.ajax({
                            type: \'POST\',
                            url: \'/www/core/ajax.php?m=ajaxs&a=setMailStatusRead\',
                            data: {"statusRead":"' . implode(",", $ids) . '"},
                            cache: false,
                            async: true,
                            dataType: \'json\',
                            success: function(response) {
                            },
                            error: function(xhr) {
                                MessageBox(xhr.statusText + xhr.responseText, true);
                            },
                        });
                    }
                };
            </script>';

        } while(false);

        return $code;
    }
}
?>