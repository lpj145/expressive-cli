<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

class CommandLoader implements CommandLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $commands;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->commands = $container->get('config')['commands'] ?? [];
    }

    public function get($name)
    {
        $className = $this->commands[$name];
        return self::tryInstanceCommand($className, $this->container);
    }

    public function has($name)
    {
        return isset($this->commands[$name]);
    }

    public function getNames()
    {
        return array_keys($this->commands);
    }

    public static function tryInstanceCommand($className, $name = null, ContainerInterface $container = null)
    {
        try {
            return new $className($name, $container);
        } catch (\Throwable $exception) {
            return new $className($name);
        }
    }
}