<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;

use mdantas\ExpressiveCli\Command\CreateCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class ExpressiveApplication extends Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container = null,
        string $name = 'UNKNOWN',
        string $version = 'UNKNOWN'
    ) {
        parent::__construct($name, $version);
        $this->container = $container;
        $this->addCommands([
            new CreateCommand('command:create')
        ]);
    }
}
