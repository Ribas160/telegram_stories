<?php

class Db extends SQLite3 {
    private $name = 'stories.db';
    public $table;


    /**
     * @todo подключаемся к базе данных
     */
    function __construct()
    {
        $this->open($this->name);
    }

    /**
     * @todo создаем таблицу в базе данных
     */
    public function create() 
    {
        $this->exec("CREATE TABLE IF NOT EXISTS " . $this->table .  " (id INTEGER PRIMARY KEY, client_id VARCHAR(11), client_points VARCHAR(11) DEFAULT 0)");
    }


    /**
     * @todo добавляем новго пользователя в базу данных
     * @param integer $uid
     */
    public function new_user($uid)
    {
        $query = $this->prepare("INSERT INTO " . $this->table . " (client_id) VALUES (:id)");
        $query->bindValue(':id', $uid);
        $query->execute();
    }


    /**
     * @todo проверяем наличие пользователя в базе данных
     * @param integer $uid
     */
    public function check_user($uid)
    {
        $query = $this->prepare("SELECT client_id FROM " . $this->table . " WHERE client_id = :id");
        $query->bindValue(':id', $uid);
        $result = $query->execute();
        $rows = $result->fetchArray();
        if (count($rows[0]) === 0) {
            self::new_user($uid);
        }
    }


    /**
     * @todo получаем колличество очков у пользователя
     * @param integer $uid
     * @return array
     */
    public function get_points($uid)
    {
        $query = $this->prepare("SELECT client_points FROM " . $this->table . " WHERE client_id = :id");
        $query->bindValue(':id', $uid);
        $result = $query->execute();
        $rows = $result->fetchArray();
        logs('id:' . $uid);
        logs('count_rows: ' . count($rows[0]));
        return $rows[0];
    }


    /**
     * @todo обновляем колличество очков у пользователя
     * @param integer $uid
     * @param integer $points
     */
    public function update_points($uid, $points)
    {
        $query = $this->prepare("UPDATE " . $this->table . " SET client_points = :points WHERE client_id = :id");
        $query->bindValue(':id', $uid);
        $query->bindValue(':points', $points);
        $query->execute();
    }
}

$story1_db = new Db();
$story1_db->table = 'ismd';
$story1_db->create();