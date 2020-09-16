<?php
include('vendor/autoload.php');
require 'logs.php';
require 'db/db.php';
$token = '1288166656:AAGTwANkiqBCSHVDPBI2PU0fOqylGVZMutg';
$bot = new \TelegramBot\Api\Client($token);

class Story
{
    public $bot;
    public $name;
    public $db;
    public $intro;

    private function get_story()
    {
        return require 'stories/' . $this->name . '.php';
    }

    private function default_commands()
    {
        $this->bot->command('start', function ($message) {
            $chatId = $message->getChat()->getid();
            $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array('Начать')), true); // true for one-time keyboard
            $this->bot->sendMessage($chatId, $this->intro, null, false, null, $keyboard);
        });

        $this->bot->command('help', function ($message) {
            $answer = 'Команды:
        /help - вывод справки';
            $this->bot->sendMessage($message->getChat()->getId(), $answer);
        });
    }


    private function telling()
    {   
        $this->bot->on(function($Update) {
            $story = self::get_story();

            $message = $Update->getMessage();
            $uid = $message->getFrom()->getid();
            $chatId = $message->getChat()->getid();
            $mtext = $message->getText();
            $this->db->check_user($uid);
            $points = self::choice($uid, $mtext);

            logs('username: ' . $message->getFrom()->getUsername() . ', user_id: ' . $uid . ', chat_id: ' . $chatId . ', message_text: ' . $mtext . ', points: ' . $points);

            // Вывод сообщений
            $answer = $story[$points]['message'];
            if (is_array($answer)) {
                foreach($answer as $value) {
                    $this->bot->sendMessage($message->getChat()->getId(), $value);
                    sleep(2);
                }
            } else {
                $this->bot->sendMessage($message->getChat()->getId(), $answer);
            }

            // Вывод выборов
            if (array_key_exists('choice', $story[$points])) {
               $choice = $story[$points]['choice'];
               $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array_keys($choice)), true);
               $this->bot->sendMessage($chatId, 'ывавыа', null, false, null, $keyboard);
            }

        }, function($message) {
            return true; 
        });
    }

    private function choice($uid, $mtext)
    {
        $story = self::get_story();
        $points = $this->db->get_points($uid);
        $choice = $story[$points]['choice'];
        if (in_array($mtext, array_keys($choice))) {
            $this->db->update_points($uid, $choice[$mtext]);
            return $choice[$mtext];
        } else {
            return $points;
        }
    }
    
    public function start()
    {
        self::default_commands();
        self::telling();
        $this->bot->run();
    }
}

$story1 = new Story();
$story1->bot = $bot;
$story1->name = 'story1';
$story1->db = $story1_db;
$story1->intro = 'Добро пожаловать в игру "Это мой район"!';
$story1->start();


