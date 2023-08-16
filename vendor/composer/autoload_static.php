<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2db72e345ec30512ba68895281f33392
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Predis\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Predis\\' => 
        array (
            0 => __DIR__ . '/..' . '/predis/predis/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2db72e345ec30512ba68895281f33392::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2db72e345ec30512ba68895281f33392::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2db72e345ec30512ba68895281f33392::$classMap;

        }, null, ClassLoader::class);
    }
}