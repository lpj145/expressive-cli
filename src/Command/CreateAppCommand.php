<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAppCommand extends Command
{

    const APP_CONSOLE_TEMPLATE = <<< 'EOT'
#!/usr/bin/env php
<?php

require '%s';
$container = %s;

(new \mdantas\ExpressiveCli\ExpressiveApplication($container))
->run();

EOT;


    protected function configure()
    {
        $this->setDescription('Create console app with container injected!');
        $this->addArgument('autoload', InputArgument::REQUIRED, 'path to composer autoload.');
        $this->addArgument('path', InputArgument::OPTIONAL, 'path to generated file, default is root dir.');
        $this->addArgument('container', InputArgument::OPTIONAL, 'path to container( service manager ).');
        $this->addArgument('name', InputArgument::OPTIONAL, 'name to generated file!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating app file based...');
        $cwd = getcwd();
        $fileName = 'app';
        $pathToFile = '/';
        $containerFile = null;

        if (null !== $input->getArgument('name')) {
            $fileName = $input->getArgument('name');
        }

        $autoloadFile = $input->getArgument('autoload');
        if (false === file_exists($cwd.$autoloadFile)) {
            throw new \ErrorException(sprintf('autoload file: %s/%s not exists!', $cwd, $autoloadFile));
        }

        $output->writeln(sprintf('<info>Using %s/%s file autoload.</info>', $cwd, $autoloadFile));

        if (null !== $input->getArgument('path')) {
            $pathToFile .= $input->getArgument('path');
        }

        if (false === is_writable($cwd.$pathToFile)) {
            mkdir($cwd.$pathToFile, 0755, true);
        }

        if (false === is_writable($cwd.$pathToFile)) {
            throw new \ErrorException('Impossible to create folder on path: '.$pathToFile);
        }

        if (null !== $input->getArgument('container')) {
            $containerFile = $input->getArgument('container');
        }

        if (null !== $containerFile && false === file_exists($containerFile)) {
            throw new \ErrorException('Container file: '.$containerFile.' not found!');
        }

        if (null !== $containerFile && !(require $containerFile) instanceof ContainerInterface) {
            throw new \ErrorException('Is not a valid psr-11 container.');
        }

        $output->writeln(sprintf('Writing file with name: %s', $fileName));
        $realPathFile = $cwd.'/'.$pathToFile.$fileName;
        if (file_exists($realPathFile)) {
            throw new \ErrorException(sprintf('File %s exists!', $realPathFile));
        }

        $template = $this->generateConsoleTemplate($autoloadFile, $containerFile);

        file_put_contents($realPathFile, $template);
        $output->writeln('<info>Console created successfully</info>');
    }

    private function generateConsoleTemplate(string $autoload, $container = null): string
    {
        if (null === $container) {
            $container = 'null';
        } else {
            $container = "'$container'";
        }

        return sprintf(self::APP_CONSOLE_TEMPLATE, $autoload, $container);
    }

}
