<?php

return [
    'token' => '1288166656:AAGTwANkiqBCSHVDPBI2PU0fOqylGVZMutg',
    'aliases' => [
        '@web' => str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__, 2) . '/index.php'),
        '@app' => dirname(__FILE__, 1),
        '@autoload' => dirname(__FILE__, 2) . '/vendor/autoload.php',
    ],
];