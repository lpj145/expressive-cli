<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;


class ComposerTools
{
    protected static $composerJson = null;

    protected static $namespacesMap = [];

    public static function getNamespaceDefinitions($namespace)
    {
        $items = array_filter(self::$namespacesMap, function ($ns) use ($namespace) {
            $result = strpos($ns, $namespace);
            return $result !== false || $result > 0;
        }, ARRAY_FILTER_USE_KEY);

        if (0 === count($items)) {
            return null;
        }

        return [
            'name' => key($items),
            'path' => $items[key($items)]
        ];
    }

    public static function loadNamespacesFromJson()
    {
        self::$namespacesMap = self::getComposerJson()['autoload']['psr-4'];
    }

    protected static function getComposerJson()
    {
        if (null !== self::$composerJson) {
            return self::$composerJson;
        }

        $pathFile = __DIR__.'/../';
        $fileName = 'composer.json';

        if (file_exists($pathFile.'../../../'.$fileName)) {
            $pathFile .= '../../../';
        }

        return self::$composerJson = json_decode(
            file_get_contents($pathFile.$fileName),
            true
        );
    }
}
