<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Command;

use mdantas\ExpressiveCli\ContainerException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugContainer extends Command
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
        $this->setDescription('Show all configured container services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ContainerException::throwIsfContainerFault($this->container);

        $containerConfig = $this->container->get('config');
        array_map(function ($config, $configName) use ($output) {
            if (is_array($config)) {
                $output->writeln($configName.':'.print_r($config, true));
                return;
            }

            $output->writeln($configName.':'.$config);
        }, $containerConfig, array_keys($containerConfig));
    }
}
