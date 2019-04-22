<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Command;

use mdantas\ExpressiveCli\ComposerTools;
use mdantas\ExpressiveCli\Contracts\CreateCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    private $commandTemplate = <<< 'EOT'
<?php
declare(strict_types=1);

namespace %s;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class %sCommand extends \Symfony\Component\Console\Command\Command 
{
    protected function configure()
    {
        //Todo insert you command description and arguments here.
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Todo Make you command by here.
    }
}
EOT;

    protected function configure()
    {
        $this->setDescription('Create command to named class');
        $this->addArgument('namespace', InputArgument::REQUIRED, CreateCommandInterface::HELP_ARGS_CLASS);
        $this->setHelp(sprintf(CreateCommandInterface::HELP_COMMAND, 'command:create'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ComposerTools::loadNamespacesFromJson();
        $fullyNamespace = $input->getArgument('namespace');
        $nsArray = explode('\\', $fullyNamespace);
        $className = end($nsArray);
        $output->writeln(sprintf('<info>Creating command %s</info>', $className));

        $nsDef = ComposerTools::getNamespaceDefinitions($nsArray[0]);
        if (null === $nsDef) {
            throw new \ErrorException('Namespace not registered on composer.json');
        }
        $pathFile = $this->getRealPath($fullyNamespace, $nsDef['name'], $nsDef['path']);

        if (file_exists($pathFile)) {
            throw new \ErrorException('File already exists!');
        }

        if (!is_writable(dirname($pathFile))) {
            $output->writeln(sprintf('<info>Creating %s folder.</info>', dirname($pathFile)));
            mkdir(dirname($pathFile), 0755, true);
        }

        if (!is_writable(dirname($pathFile))) {
            throw new \ErrorException(sprintf('Folder: %s is not writeable!', dirname($pathFile)));
        }

        file_put_contents($pathFile, sprintf($this->commandTemplate, implode('\\', $nsArray), $className));
        $output->writeln(sprintf('Created class on path: %s', $pathFile));
        $output->writeln('<info>Command created successfully</info>');
    }

    private function getRealPath(string $fullyName, string $composerNS, string $nsPath, string $cwd = null) : string
    {
        if (is_null($cwd)) {
            $cwd = getcwd();
        }

        $fullyName = substr($fullyName, strlen($composerNS));

        return sprintf('%s/%s%s.php', $cwd, $nsPath, $fullyName);
    }

}
