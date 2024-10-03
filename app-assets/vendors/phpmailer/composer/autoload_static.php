<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita9523b96b1bb217c70275bdb234f724d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita9523b96b1bb217c70275bdb234f724d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita9523b96b1bb217c70275bdb234f724d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita9523b96b1bb217c70275bdb234f724d::$classMap;

        }, null, ClassLoader::class);
    }
}
