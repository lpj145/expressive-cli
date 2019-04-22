<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;

use mdantas\ExpressiveCli\Command\CreateCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Zend\Expressive\Tooling\CreateMiddleware\CreateMiddlewareCommand;
use Zend\Expressive\Tooling\Factory\CreateFactoryCommand;
use Zend\Expressive\Tooling\MigrateInteropMiddleware\MigrateInteropMiddlewareCommand;
use Zend\Expressive\Tooling\MigrateMiddlewareToRequestHandler\MigrateMiddlewareToRequestHandlerCommand;
use Zend\Expressive\Tooling\Module\DeregisterCommand;
use Zend\Expressive\Tooling\Module\RegisterCommand;

class ExpressiveApplication extends Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container = null,
        string $name = 'expressive-cli',
        string $version = '1.0'
    ) {
        parent::__construct($name, $version);
        $this->container = $container;


        if (null !== $container) {
            $this->setCommandLoader(
                $container->get(CommandLoaderInterface::class)
            );
        }

        if (is_null($container)) {
            $this->setCommandsWithoutContainer();
        }
    }

    private function setCommandsWithoutContainer()
    {
        $this->addCommands([
            new CreateFactoryCommand('factory:create'),
            new CreateMiddlewareCommand('middleware:create'),
            new MigrateInteropMiddlewareCommand('migrate:interop-middleware'),
            new MigrateMiddlewareToRequestHandlerCommand('migrate:middleware-to-request-handler'),

            new \Zend\Expressive\Tooling\Module\CreateCommand('module:create'),
            new DeregisterCommand('module:deregister'),
            new RegisterCommand('module:register'),

            //Expressive-cli commands
            new CreateCommand('command:create')
        ]);
    }
}
