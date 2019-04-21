<?php
declare(strict_types=1);

namespace mdantas\ExpressiveCli\Command;


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
        //Make you command by here.
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
        $output->writeln($this->commandTemplate);
    }

}
