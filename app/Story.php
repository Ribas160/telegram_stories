<?php

class Story
{

    public function get_stories_names()
    {
        $stories = glob('stories/*');
        $stories_names = [];
        foreach($stories as $story) {
            $story_set = require $story;
            $stories_names[] = $story_set['name'];
        }
        return $stories_names;
    }

    public function get_story($name)
    {
        $stories = glob('stories/*');
        foreach($stories as $story) {
            $story_set = require $story;
            if ($story_set['name'] === $name) {
                return $story_set;
            }
        }
    }

    public function get_messages($story_name, $points)
    {
        $story = self::get_story($story_name);
        $messages = [];
        $last_message = '';
        $messages_count = count($story[$points]['message']);

        // отдельно получаем последнее сообщение из истории для отправки вместе с клавиатурой
        if ($messages_count > 1) {
            for ($i=0; $i <= ($messages_count - 2); $i++) { 
                $messages[] = $story[$points]['message'][$i];
            }
            $last_message = $story[$points]['message'][$messages_count - 1];
            return [
                'messages' => $messages,
                'last_message' => $last_message,
            ];
        } else {
            return [
                'messages' => $messages,
                'last_message' => $story[$points]['message'][0],
            ];
        }
    }
}