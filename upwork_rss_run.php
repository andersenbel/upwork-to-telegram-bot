<?php
chdir(__DIR__);
require_once("./TelegramBotCl.php");
require_once("./BlogFeed.php");
$bot = new TelegramBotCl();
$posts = new BlogFeed($bot->cfg->UPWORK_RSS_URL);
$ts = json_decode(file_get_contents("./ts.json"), true);
if (!is_array($ts)) $ts = array();
$keyboard = false;
foreach ($posts->posts as $post) {
    if (isset($post->ts) && !array_key_exists($post->ts, $ts) && is_numeric($post->ts)) {
        $ts[$post->ts] = date("Y-m-d H:i:s");
        $message = "
$post->date

$post->link

$post->title

" . html_entity_decode(strip_tags($post->text)) . "
";
        $bot->sendMessage($message, $bot->cfg->TELEGRAM_USER_ID);
    }
}
file_put_contents("./ts.json", json_encode($ts));
