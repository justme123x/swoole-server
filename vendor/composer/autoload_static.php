<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit54ec67be43ee0547d1fd6b2e3e4655d7
{
    public static $files = array (
        'ac70bc6774c96154b34a572818255d13' => __DIR__ . '/../..' . '/lib/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lib\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lib\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static $classMap = array (
        'Lib\\Handles\\GiftHandle' => __DIR__ . '/../..' . '/lib/Handles/GiftHandle.php',
        'Lib\\Handles\\Handle' => __DIR__ . '/../..' . '/lib/Handles/Handle.php',
        'Lib\\Services\\GiftClientService' => __DIR__ . '/../..' . '/lib/Services/GiftClientService.php',
        'Lib\\Services\\GiftServerService' => __DIR__ . '/../..' . '/lib/Services/GiftServerService.php',
        'Lib\\Utils\\LogUtils' => __DIR__ . '/../..' . '/lib/Utils/LogUtils.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit54ec67be43ee0547d1fd6b2e3e4655d7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit54ec67be43ee0547d1fd6b2e3e4655d7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit54ec67be43ee0547d1fd6b2e3e4655d7::$classMap;

        }, null, ClassLoader::class);
    }
}