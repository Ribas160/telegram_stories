<?php

class Db extends SQLite3 {
    private $name = 'stories.db';
    private $table = 'users';


    /**
     * @todo подключаемся к базе данных
     */
    function __construct()
    {
        $this->open($this->name);
    }

    /**
     * создаем таблицу в базе данных
     */
    public function create() 
    {
        $this->exec("CREATE TABLE IF NOT EXISTS " . $this->table .  " (id INTEGER PRIMARY KEY, story VARCHAR(255), user_id VARCHAR(11), user_name VARCHAR(255), user_points VARCHAR(255) DEFAULT 0, active VARCHAR(1) DEFAULT 0)");
    }


    /**
     * добавляем новго пользователя в базу данных
     * @param integer $uid
     */
    public function new_user($uid, $username, $story)
    {
        $query = $this->prepare("INSERT INTO " . $this->table . " (user_id, user_name, story) VALUES (:id, :name, :story)");
        $query->bindValue(':id', $uid);
        $query->bindValue(':name', $username);
        $query->bindValue(':story', $story);
        $query->execute();
    }


    /**
     * проверяем наличие пользователя в базе данных
     * @param integer $uid
     */
    public function check_user($uid, $username, $story)
    {
        $query = $this->prepare("SELECT user_id FROM " . $this->table . " WHERE user_id = :id AND story = :story");
        $query->bindValue(':id', $uid);
        $query->bindValue(':story', $story);
        $result = $query->execute();
        $rows = $result->fetchArray();
        if (count($rows[0]) === 0) {
            self::new_user($uid, $username, $story);
        }
    }


    /**
     * получаем колличество очков у пользователя
     * @param integer $uid
     * @return string
     */
    public function get_points($uid, $story)
    {
        $query = $this->prepare("SELECT user_points FROM " . $this->table . " WHERE user_id = :id AND story = :story");
        $query->bindValue(':id', $uid);
        $query->bindValue(':story', $story);
        $result = $query->execute();
        $rows = $result->fetchArray();
        return $rows[0];
    }


    /**
     * обновляем колличество очков у пользователя
     * @param integer $uid
     * @param integer $points
     */
    public function update_points($uid, $story, $points)
    {
        $query = $this->prepare("UPDATE " . $this->table . " SET user_points = :points WHERE user_id = :id AND story = :story");
        $query->bindValue(':id', $uid);
        $query->bindValue(':points', $points);
        $query->bindValue(':story', $story);
        $query->execute();
    }

    /**
     * сбрасываем очки у пользователя
     * @param integer $uid
     */
    public function reset_points($uid)
    {
        $query = $this->prepare("UPDATE " . $this->table . " SET user_points = :points WHERE user_id = :id");
        $query->bindValue(':id', $uid);
        $query->bindValue(':points', 0);
        $query->execute();
    }


    /**
     * получаем название истории пользователя
     * @param integer $uid
     * @return string
     */
    public function get_story($uid)
    {
        $query = $this->prepare("SELECT story FROM " . $this->table . " WHERE user_id = :id AND active = :active");
        $query->bindValue(':id', $uid);
        $query->bindValue(':active', 1);
        $result = $query->execute();
        $rows = $result->fetchArray();
        return $rows[0];
    }


    /**
     * объявляем активную историю пользователя
     * @param integer $uid
     */
    public function active_enable($uid, $story)
    {
        self::active_disable($uid);

        $query = $this->prepare("UPDATE " . $this->table . " SET active = :active WHERE user_id = :id AND story = :story");
        $query->bindValue(':id', $uid);
        $query->bindValue(':story', $story);
        $query->bindValue(':active', 1);
        $query->execute();
    }

    /**
     * сбрасываем активность истории пользователя
     * @param integer $uid
     */
    public function active_disable($uid)
    {
        $query = $this->prepare("UPDATE " . $this->table . " SET active = :active WHERE user_id = :id");
        $query->bindValue(':id', $uid);
        $query->bindValue(':story', $story);
        $query->bindValue(':active', 0);
        $query->execute();
    }
}

