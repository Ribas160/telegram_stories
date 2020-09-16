<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit423573e52c8339a46bea7bcb43b6f46f
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TelegramBot\\Api\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TelegramBot\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/telegram-bot/api/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit423573e52c8339a46bea7bcb43b6f46f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit423573e52c8339a46bea7bcb43b6f46f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
