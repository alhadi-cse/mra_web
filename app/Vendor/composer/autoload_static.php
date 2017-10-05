<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit186ba8cf51620790dcb9d84210c8b376
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Ghunti\\HighchartsPHP\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ghunti\\HighchartsPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/ghunti/highcharts-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit186ba8cf51620790dcb9d84210c8b376::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit186ba8cf51620790dcb9d84210c8b376::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
