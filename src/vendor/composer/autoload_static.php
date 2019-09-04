<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc8d21ce384b97245e61a9a931fb88992
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Component\\Yaml\\' => 23,
        ),
        'P' => 
        array (
            'PaygentModule\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'PaygentModule\\' => 
        array (
            0 => __DIR__ . '/..' . '/paygent/connect/src/paygent_module',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc8d21ce384b97245e61a9a931fb88992::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc8d21ce384b97245e61a9a931fb88992::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}