<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }
}
