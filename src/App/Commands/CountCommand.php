<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'count')]
class CountCommand extends Command
{
    protected function configure() : void
    {
        $this->addArgument('path', inputArgument::REQUIRED);
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        if(file_exists($path))
        {
            $output->writeln($this->count($path));
            return Command::SUCCESS;
        }
        $output->writeln("false");
        return Command::INVALID;
    }
    private function count($path)
    {
        $result = 0;
        if(is_dir($path))
        {
            $dir = opendir($path);
            while($file = readdir($dir))
            {
                if($file != '.' && $file != '..')
                {
                    if(is_dir("$path/$file"))
                    {
                        $result += $this->count("$path/$file");
                    }
                    else if($file == "count")
                    {
                        $content = file("$path/$file");
                        $result += intval($content[0]);
                    }
                }
            }
        }

        return $result;
    }
}