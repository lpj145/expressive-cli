<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Command;

use mdantas\ExpressiveCli\ContainerException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Router\Route;

class DebugRoutes extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(string $name = null, ContainerInterface $container = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function configure()
    {
        $this->setDescription('Show all registered routes on container!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ContainerException::throwIsfContainerFault($this->container);
        ContainerException::throwIfServiceNotFound($this->container, Application::class);
        /** @var Application $application */
        $application = $this->container->get(Application::class);

        $table = new Table($output);
        $table
            ->setHeaders([
                'name',
                'method',
                'invoker',
                'url'
            ]);

        $routes = $application->getRoutes();
        $tableRows = array_map(function (Route $router) {
            $middlewareClass = $router->getMiddleware();
            $handlerProperty = (new \ReflectionClass($middlewareClass))
                ->getProperty('handler');
            $handlerProperty->setAccessible(true);

            return [
                $router->getName(),
                implode(', ', $router->getAllowedMethods()),
                get_class($handlerProperty->getValue($middlewareClass)),
                $router->getPath()
            ];
        }, $routes);


        $table->setRows($tableRows);
        $table->render();
        $output->writeln(sprintf('Founded %d registered route(s)!', count($routes)));
    }
}
