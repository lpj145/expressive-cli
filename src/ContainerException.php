<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;

use Psr\Container\ContainerInterface;

class ContainerException
{
    public static function throwIsfContainerFault($container)
    {
        if (null === $container) {
            throw new \ErrorException('Container is null, is not valid container!');
        }

        if (!$container instanceof ContainerInterface) {
            throw new \ErrorException('Container is not a valid psr-11.');
        }
    }

    public static function throwIfServiceNotFound(
        ContainerInterface $container,
        string $serviceName,
        string $throwMessage = null
    ) {
        if (!$container->has($serviceName)) {
            throw new \ErrorException($throwMessage ?? sprintf('Service %s not found on container!', $serviceName));
        }
    }
}
