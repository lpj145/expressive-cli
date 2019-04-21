<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;


class ComposerTools
{
    public static function checkNamespaceRegistered($namespace)
    {
        $namespaces = static::getComposerJson()['autoload']['psr-4'];
        var_dump($namespaces);

    }

    protected static function getComposerJson()
    {
        $pathFile = __DIR__.'/../';
        $fileName = 'composer.json';

        if (file_exists($pathFile.'../../../'.$fileName)) {
            $pathFile .= '../../../';
        }

        return json_decode(
            file_get_contents($pathFile.$fileName),
            true
        );
    }
}
