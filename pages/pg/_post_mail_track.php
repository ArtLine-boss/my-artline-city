<?php

require $_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer/src/PHPMailer.php';

$email = $_GET['mail'];
$cod = $_GET['cod'];

$mail = new PHPMailer();
$mail->setFrom('boot@artline.biz');
$mail->addAddress($email);
$mail->addAddress('6@artline.biz');

$mail->Subject = 'АРТЛАЙНСИТИ. ТРЕК-КОД ДЛЯ ОТСЛЕЖИВАНИЯ ЗАКАЗА.';
$mail->CharSet = PHPMailer::CHARSET_UTF8;

$message = '<div style="display: flex; flex-direction: column;">
    <p>Добрый день.</p>
    <div style="display: flex; align-items: baseline;"><p style="margin-right: 10px;">Ваш трек-код</p>
        <p style="font-size: 20pt; font-weight: bold;">' . $cod . '</p></div>
    <p>Отследить можно на сайте <a href="https://belpost.by/Otsleditotpravleniye?number=' . $cod . '" target="_blank"><b>https://belpost.by/</b></a>
    </p>
    <p style="font-weight: bold; color: red;">Это электронное письмо было отправлено автоматически. Пожалуйста, не
        отвечайте на него.</p>
    <p style="margin: 0">По всем вопросам обращайтесь к</p>
    <p style="margin: 0"><b>Анне Сафроновой</b>,</p>
    <p style="margin: 0">специалисту по работе с клиентами</p>
    <p style="margin: 0">ООО "Мечта клиента"</p>
    <p style="margin: 0">224030 г. Брест, ул. Карбышева, 74</p>
    <p style="margin: 0"><b>тел.:</b> (033) 644-44-77</p>
    <p style="margin: 0"><b>viber:</b> +375336444477</p>
    <p style="margin: 0"><b>telegram:</b> @Artliner_by</p>
    <p style="margin: 0"><b>mailto:</b> 6@artline.biz</p>
    <p style="margin: 1em 0 0;">Время работы: 8.00-12.00, 13.00-17.00</p>
    <p style="margin: 0">Выходные: сб, вс</p>
</div>P';

$mail->msgHTML($message);
$mail->send();
?>



