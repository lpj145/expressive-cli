<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli;


use mdantas\ExpressiveCli\Command\DebugContainer;
use mdantas\ExpressiveCli\Command\DebugRoutes;
use mdantas\ExpressiveCli\CommandLoader;
use mdantas\ExpressiveCli\Factories\CommandLoaderFactory;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Zend\Expressive\Tooling\CreateHandler\CreateHandlerCommand;
use Zend\Expressive\Tooling\CreateMiddleware\CreateMiddleware;
use Zend\Expressive\Tooling\Factory\CreateFactoryCommand;
use Zend\Expressive\Tooling\MigrateInteropMiddleware\MigrateInteropMiddlewareCommand;
use Zend\Expressive\Tooling\MigrateMiddlewareToRequestHandler\MigrateMiddlewareToRequestHandlerCommand;
use Zend\Expressive\Tooling\Module\CreateCommand;
use Zend\Expressive\Tooling\Module\DeregisterCommand;
use Zend\Expressive\Tooling\Module\RegisterCommand;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    CommandLoader::class => CommandLoaderFactory::class
                ],
                'aliases' => [
                    CommandLoaderInterface::class => CommandLoader::class
                ]
            ],
            'commands' => [
                //expressive-cli commands
                'routes:debug' => DebugRoutes::class,
                'container:debug' => DebugContainer::class,
                'command:create' => CreateCommand::class,
                //expressive-tooling commands
                'factory:create' => CreateFactoryCommand::class,
                'middleware:create' => CreateMiddleware::class,
                'migrate:interop-middleware' => MigrateInteropMiddlewareCommand::class,
                'migrate:middleware-to-request-handler' => MigrateMiddlewareToRequestHandlerCommand::class,
                // Modules commands
                'module:create' => CreateCommand::class,
                'module:deregister' => DeregisterCommand::class,
                'module:register' => RegisterCommand::class,
                //Handlers commands
                'action:create' => CreateHandlerCommand::class,
                'handler:create' => CreateHandlerCommand::class
            ]
        ];
    }
}
