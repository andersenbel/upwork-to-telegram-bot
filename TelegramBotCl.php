<?php

use CfgTelegram;

class TelegramBotCl {
  function __construct() {
    $this->cfg = new CfgTelegram();
    $this->BOT_TOKEN = $this->cfg->BOT_TOKEN;
    $this->API_URL = 'https://api.telegram.org/bot' . $this->BOT_TOKEN . '/';
    $this->WEBHOOK_URL = $this->cfg->WEBHOOK_URL;
  }
  function setWebhook($arg) {
    $this->apiRequest('setWebhook', array('url' => isset($arg) && $arg == 'delete' ? '' : $this->WEBHOOK_URL));
    echo "Webhook has been set: {$this->WEBHOOK_URL}";
    exit;
  }
  function apiRequestWebhook($method, $parameters) {
    if (!is_string($method)) {
      error_log("Method name must be a string\n");
      return false;
    }
    if (!$parameters) {
      $parameters = array();
    } else if (!is_array($parameters)) {
      error_log("Parameters must be an array\n");
      return false;
    }
    $parameters["method"] = $method;
    $payload = json_encode($parameters);
    header('Content-Type: application/json');
    header('Content-Length: ' . strlen($payload));
    echo $payload;
    return true;
  }

  function execCurlRequest($handle) {
    $response = curl_exec($handle);
    if ($response === false) {
      $errno = curl_errno($handle);
      $error = curl_error($handle);
      error_log("Curl returned error $errno: $error\n");
      curl_close($handle);
      return false;
    }
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
    if ($http_code >= 500) {
      // do not wat to DDOS server if something goes wrong
      sleep(10);
      return false;
    } else if ($http_code != 200) {
      $response = json_decode($response, true);
      error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
      if ($http_code == 401) {
        throw new Exception('Invalid access token provided');
      }
      return false;
    } else {
      $response = json_decode($response, true);
      if (isset($response['description'])) {
        error_log("Request was successful: {$response['description']}\n");
      }
      $response = $response['result'];
    }
    return $response;
  }

  function apiRequest($method, $parameters, $post_fields = array(), $multipart = false) {
    if (!is_string($method)) {
      error_log("Method name must be a string\n");
      return false;
    }
    if (!$parameters) {
      $parameters = array();
    } else if (!is_array($parameters)) {
      error_log("Parameters must be an array\n");
      return false;
    }
    if ($parameters) {
      $parameters = '?' . http_build_query($parameters);
    } else {
      $parameters = '';
    }
    $url = $this->API_URL . $method . $parameters;
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    if (sizeof($post_fields) > 0) {
      curl_setopt($handle, CURLOPT_POSTFIELDS, $post_fields);
    }
    if ($multipart)
      curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));

    return $this->execCurlRequest($handle);
  }

  function sendMessage($text, $uid, $reply_markup = false) {
    $par = array(
      'chat_id' => $uid,
      'text' => $text,
      'parse_mode' => 'HTML',
      'disable_web_page_preview' => false,
    );
    if (is_array($reply_markup))
      $par['reply_markup'] = json_encode($reply_markup);
    $this->apiRequest("sendMessage", $par);
  }
}
