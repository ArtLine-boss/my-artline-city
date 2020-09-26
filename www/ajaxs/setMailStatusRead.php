<?php
$param = (isset($_POST['statusRead']) && !empty($_POST['statusRead'])) ? $_POST['statusRead'] : '';
$data = explode(',', $param);
foreach ($data as $k => $mail_id) {
    $mail = new entity_mail();
    if(null === ($msg = $mail->loadById($mail_id))) {
        $mail->status_read = 1;
        $mail->store();
    }
    unset($mail);
}
?>