<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit8e8a6b50e44a9ff72ae672a77d75fecf
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit8e8a6b50e44a9ff72ae672a77d75fecf', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit8e8a6b50e44a9ff72ae672a77d75fecf', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit8e8a6b50e44a9ff72ae672a77d75fecf::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
