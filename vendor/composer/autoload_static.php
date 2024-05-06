<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4191fa10878fba9e4cb77131ecc00681
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Config\\' => 7,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Config\\' => 
        array (
            0 => __DIR__ . '\..\..' . '\Config',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '\..\..' . '\App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '\..' . '\composer\InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4191fa10878fba9e4cb77131ecc00681::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4191fa10878fba9e4cb77131ecc00681::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4191fa10878fba9e4cb77131ecc00681::$classMap;

        }, null, ClassLoader::class);
    }
}
