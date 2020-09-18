<?php

class Logs
{
    public function set_log($data)
    {
        $config = require dirname(__FILE__, 1) . '/Config.php';
        if (!file_exists($config['aliases']['@app'] . '/logs')) {
            mkdir($config['app'] . '/logs', 0755);
        }
        $path = $config['aliases']['@app'] . '/logs/' . date('Y-m-d') . '.txt';
        $fp = fopen($path, 'a+');
        if (is_array($data)) {
            $text = '[';
            foreach($data as $key => $value) {
                $text .= $key . '=>' . $value . ', ';
            }
            $text .= ']';
            $log = date('Y-m-d H:i:s') . ' ' . $text . PHP_EOL;
        } else {
            $log = date('Y-m-d H:i:s') . ' ' . $data . PHP_EOL;
        }
        fwrite($fp, $log);
        fclose($fp);
    }
}