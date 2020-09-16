<?php
function logs($data) {
    if (!file_exists('logs')) {
        mkdir(dirname(__FILE__, 1) . '/logs', 0755);
    }
    $path = dirname(__FILE__, 1) . '/logs/' . date('Y-m-d') . '.txt';
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