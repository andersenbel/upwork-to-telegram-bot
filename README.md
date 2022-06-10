Upwork to Telegram
==========

Application filters new jobs on Upwork and send them to Telegram.

First of all, you need to get Upwork URL. You can find it in the `Feed Links -> RSS` part of your job feed.

<img src="https://community.upwork.com/t5/image/serverpage/image-id/18552iEA26C79EE35BAD7D/image-dimensions/2500?v=v2&px=-1">

Also, you can read Upwork community suggestions "[How to use targeted RSS feeds to automatic job alerts](https://community.upwork.com/t5/New-to-Upwork/How-to-use-targeted-RSS-feeds-to-automatic-job-alerts/m-p/638347)", URL should be set as below:

        var $UPWORK_RSS_URL = "https://www.upwork.com/ab/feed/topics/rss?securityToken=xyz...&userUid=123...&orgUid=123...";


Create Telegram Bot
==========

Use `@BotFather` and follow instructions. When a chatbot will be created, you should save a chatbot token, it should be set as below:

    var $BOT_TOKEN = '123...:AAG...';


Install
==========

Upload this repository to your hosting directory, and determine the webhook URL. It should be set as below:

    https://your-site.com/path-to-upwork2telegram/hook.php

Edit setting of configuring file `cfg.php`:

    {
        var $BOT_TOKEN = '123...:AAG...';
        var $WEBHOOK_URL = 'https://your-site.com/path-to-upwork2telegram/hook.php';
        var $UPWORK_RSS_URL = "https://www.upwork.com/ab/feed/topics/rss?securityToken=xyz...&userUid=123...&orgUid=123...";
        var $TELEGRAM_USER_ID = '123...';
    }

In the browser open the webhook URL and add  to the end `?webhook`:     

    https://your-site.com/path-to-upwork2telegram/hook.php?webhook

You should to see a message as below:

    Webhook has been set: https://your-site.com/path-to-upwork2telegram/hook.php

Get a user id by sending `uid` message to the chatbot. Replace user id in `cfg.php`:

    var $TELEGRAM_USER_ID = '123...';

Add command to cron :  

    */15 * * * * php -f /path-to-app/upwork_rss_run.php

Screenshot
==========

<img src="https://raw.githubusercontent.com/andersenbel/Upwork-To-Telegram-Bot/main/images/scr1.jpg">    