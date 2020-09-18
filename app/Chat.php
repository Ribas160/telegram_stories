<?php

class Chat
{
    private $db;
    private $telegram;
    private $uid;
    private $chatId;
    private $username;
    private $message;
    private $story;
    private $points;
    

    private function get_info()
    {
        $this->db = new Db();
        $this->telegram = Connecting::connect();
        $info = $this->telegram->getWebhookUpdates();
        $this->uid = $info['message']['from']['id'];
        $this->chatId = $info['message']['chat']['id'];
        $this->username = $info['message']['from']['username'];
        $this->message = $info['message']['text'];
        
        $this->db->create();
        Logs::set_log('user_info: ' . 'uid=' . $this->uid . ', chatId=' . $this->chatId . ', username=' . $this->username . ', message=' . $this->message);
    }

    private function commands()
    {   
        $stories = Story::get_stories_names();


        if ($this->message === '/start') {

            $keyboard = [];
            foreach($stories as $story) {
                $keyboard[] = [$story];
            }

            self::keyboard($keyboard, 'Привет! Выберите историю, в которую хотите сыграть.');
        } elseif ($this->message === '/help') {
            $text = 'Для начала игры введите команду /start. При начале новой игры прогресс сбрасывается. Приятно игры.';
            self::message($text);
        }
    }

    private function telling()
    {
        $stories = Story::get_stories_names();
        if (in_array($this->message, $stories)) {
            $this->db->check_user($this->uid, $this->username, $this->message);
            $this->db->active_enable($this->uid, $this->message);
            $this->db->reset_points($this->uid);
        }
        self::choice();
        Logs::set_log('story: ' . $this->story['name'] . ', points: ' . $this->points);
        if ($this->points == 0 && $this->message !== 'Начать') {
            $keyboard = [['Начать']];
            self::keyboard($keyboard, $this->story['intro']);
        } else {
            $messages = Story::get_messages($this->story['name'], $this->points);
            if (!empty($messages['messages'])) {
                foreach($messages['messages'] as $message) {
                    self::message($message);
                    sleep(2);
                }
            }
            if (array_key_exists('choice', $this->story[$this->points])) {
                $keyboard = [];
                foreach($this->story[$this->points]['choice'] as $key => $choice) {
                    $keyboard[] = [$key];
                }
                self::keyboard($keyboard, $messages['last_message']);
            } else {
                self::message($messages['last_message']);
            }
        }
    }


    private function choice()
    {
        $user_story = $this->db->get_story($this->uid);
        $this->story = Story::get_story($user_story);
        $this->points = $this->db->get_points($this->uid, $this->story['name']);
        $choice = $this->story[$this->points]['choice'];
        if (in_array($this->message, array_keys($choice))) {
            $this->points = $this->story[$this->points]['choice'][$this->message];
            $this->db->update_points($this->uid, $this->story['name'], $this->points);
        }
    }

    private function keyboard($keyboard, $text)
    {
        $reply_markup = Telegram\Bot\Keyboard\Keyboard::make([
            'keyboard' => $keyboard, 
            'resize_keyboard' => true, 
            'one_time_keyboard' => true,
        ]);
        
        $this->telegram->sendMessage([
            'chat_id' => $this->chatId, 
            'text' => $text, 
            'reply_markup' => $reply_markup,
        ]);
    }

    private function message($message)
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->chatId, 
            'text' => $message, 
        ]);
    }


    public function init()
    {
        self::get_info();
        if (substr($this->message, 0, 1) === '/') {
            self::commands();
        } else {
            self::telling();
        }
    }
}

$chat = new Chat();
$chat->init();