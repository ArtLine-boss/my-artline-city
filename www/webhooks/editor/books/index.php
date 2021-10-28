<?php
$AppUI = new core_ui(true);
try {
    $webhook = entity_webhooks::loadWebhook($_SERVER['REDIRECT_URL'], false);
    if (!$webhook->getInit()) {
        $log = new core_log('webhooks/errors.log');
        $log->store('Not find webhook ' . $_SERVER['REDIRECT_URL']);
        $AppUI->_redirect403();
    }
    webhooks_editor_books_Manager::getInstance()->setWebhook($webhook);

    // Выводим страницу
    webhooks_editor_books_Manager::getInstance()->view();

} catch (Exception $ex) {
    $log = new core_log('webhooks/errors.log');
    $log->store($ex->getCode() . '. File ' . $ex->getFile() . '. Line ' . $ex->getLine() . '. ' . $ex->getMessage());
    $AppUI->_redirect403();
}