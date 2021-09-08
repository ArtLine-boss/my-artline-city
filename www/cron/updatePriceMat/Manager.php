<?php
class cron_updatePriceMat_Manager
{
    protected $listMat;

    const COUNT_MONTHLY = 12;
    const MAIL_TO = [
        'wladfm.prylutski@gmail.com',
        'wladislove_best@mail.ru'
    ];

    public function __construct()
    {
    }

    /**
     * @param cron_updatePriceMat_Dto $dto
     */
    public function setListMat($dto) {
        if(empty($this->listMat) || !is_array($this->listMat)) {
            $this->listMat = [];
        }
        if(!isset($this->listMat[$dto->matId]) || $this->listMat[$dto->matId]->newPrice < $dto->newPrice) {
            $this->listMat[$dto->matId] = $dto;
        }
    }

    /**
     * @return array
     */
    public function getListMat() {
        return empty($this->listMat) || !is_array($this->listMat) ? [] : $this->listMat;
    }

    public function proccess() {
        $res = [];

        do {
            $filter_date = API::addMonth(API::CurrentDate(), -static::COUNT_MONTHLY, CONSTANTS::DB_DATE_FORMAT);

            $sql = "SELECT 
                        `material_attr`.`ID` `matId`, 
                        `material_attr`.`M_NAME` `matName`, 
                        `material_attr`.`M_PRICE` `price`,
                        `material_attr`.`M_KOL_ALL` `currentQ`,
                        `ttn_mater`.`total` `ttnTotal`, 
                        `ttn_mater`.`sum_all` `ttnSum`,
                        `ttn`.`num` `ttnNum`,
                        `ttn`.`dt` `ttnDate`
                    FROM `material_attr`
                    JOIN `ttn_mater` ON `ttn_mater`.`id_mat` = `material_attr`.`ID`
                    JOIN `ttn` ON `ttn`.`ID` = `ttn_mater`.`id_TTN`
                    WHERE `ttn`.`dt` >= '" . $filter_date . "'";
            $stocks = core_DBObject::s_select($sql);
            foreach ($stocks as $data) {
                $dto = new cron_updatePriceMat_Dto();
                $dto->bind($data);
                if($dto->isUpdate()) {
                    $this->setListMat($dto);
                }
                unset($dto);
            }
        } while(false);

        return $res;
    }

    public function mail($filepath = '') {
        require $_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer/src/PHPMailer.php';
        $mail = new PHPMailer();
        $mail->setFrom('boot@artline.biz');
        foreach (static::MAIL_TO as $email) {
            $mail->addAddress($email);
        }
        $mail->Subject = 'Результат работы скрипта обновления цен на материалы';
        $mail->msgHTML('Данное сообщение <b>сгенерировано автоматически</b>. Результат работы скрипта представлен <b>в прикрепленном файле</b>.');
        if(!empty($filepath)) {
            $mail->addAttachment($filepath);
        }
        return $mail->send();
    }
}
?>