<?php

$id_order = $_GET['id_order'];
$id_client = $_GET['id_client'];
$mail = $_GET['mail'];


$strr = "proc/pdf/pdf".$id_order."_".$id_client.".pdf";

  $filename = $strr ; //Имя файла для прикрепления
  $to = $mail ; //Кому
  $from = "boot@artline.biz"; //От кого
  $subject = "АРТЛАЙНСИТИ. ДОКУМЕНТЫ."; //Тема
  $message = "На Ваш запрос отправляем счет-фактуру.
  
  
  Это электронное письмо было отправлено автоматически. Пожалуйста, не отвечайте на него.
  
  
  
  "; //Текст письма
  $boundary = "---"; //Разделитель
  /* Заголовки */
  $headers = "From: $from\nReply-To: $from\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
  $body = "--$boundary\n";
  /* Присоединяем текстовое сообщение */
  $body .= "Content-type: text/html; charset='utf-8'\n";
  $body .= "Content-Transfer-Encoding: quoted-printablenn";
  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
  $body .= $message."\n";
  $body .= "--$boundary\n";
  $file = fopen($filename, "r"); //Открываем файл
  $text = fread($file, filesize($filename)); //Считываем весь файл
  fclose($file); //Закрываем файл
  /* Добавляем тип содержимого, кодируем текст файла и добавляем в тело письма */
  $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n"; 
  $body .= "Content-Transfer-Encoding: base64\n";
  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
  $body .= chunk_split(base64_encode($text))."\n";
  $body .= "--".$boundary ."--\n";

  mail($to, $subject, $body, $headers); //Отправляем письмо
?>



