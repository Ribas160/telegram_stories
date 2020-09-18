<?php
include dirname(__FILE__, 2) . '/vendor/autoload.php';
use Telegram\Bot\Api; 
class Connecting
{
    
    private function set_webhook($config)
    {
        $url = 'https://api.telegram.org/bot' . $config['token'] . '/setWebhook?url=' . $_SERVER['SERVER_NAME'] . $config['aliases']['@web'];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
        ]);
        $data = curl_exec($ch);
        curl_close($ch);
        Logs::set_log('webhook: ' . $data);
    }

    public function connect()
    {
        $config = require dirname(__FILE__, 1) . '/Config.php';
        self::set_webhook($config);
        $telegram = new Api($config['token']);
        return $telegram;
    }
}