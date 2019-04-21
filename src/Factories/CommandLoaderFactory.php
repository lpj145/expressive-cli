<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Factories;


use mdantas\ExpressiveCli\CommandLoader;
use Psr\Container\ContainerInterface;

class CommandLoaderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CommandLoader($container);
    }
}
