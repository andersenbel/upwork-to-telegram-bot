<?php
chdir(__DIR__);
require_once("./TelegramBotCl.php");
$bot = new TelegramBotCl();
if (isset($_GET['webhook'])) {
    $bot->setWebhook('');
} else {
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    if (!$update) {
        // receive wrong update, must not happen
        exit;
    } elseif (
        isset($update["message"]) &&
        isset($update["message"]["text"]) &&
        isset($update["message"]["from"]) &&
        isset($update["message"]["from"]['id']) &&
        $update["message"]["text"] == 'uid'
    ) {
        $bot->sendMessage($update["message"]["from"]['id'], $bot->cfg->TELEGRAM_USER_ID);
    }
}
