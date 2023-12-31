<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit626611e95a45e4a7817ad71de35dffd6
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'BitbossHub\\GitVersionOutput\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'BitbossHub\\GitVersionOutput\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit626611e95a45e4a7817ad71de35dffd6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit626611e95a45e4a7817ad71de35dffd6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit626611e95a45e4a7817ad71de35dffd6::$classMap;

        }, null, ClassLoader::class);
    }
}
