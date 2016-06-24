<?php
namespace SiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('custom:cache-clear')
            ->setDescription('Clear app cache and memcached')
            ->addOption('only-app', null, InputOption::VALUE_NONE, 'Only app cache clear');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $kernel;
        $app_dir = $kernel->getRootDir();


        exec("echo 'wtpt8sspib' | sudo rm -rf $app_dir/cache && echo 'wtpt8sspib' | sudo rm -rf $app_dir/logs");

        $time_start = microtime(true);
        if (!$input->getOption('only-app')) {
            exec($app_dir."/console cache:clear --env=dev && ". $app_dir."/console cache:clear --env=prod && echo \"flush_all\" | nc -q 2 localhost 11211 && rm -rf $app_dir/../web/static/assets/js/compiled/* && rm -rf $app_dir/../web/static/assets/css/compiled/* && $app_dir/console assetic:dump --no-debug");
        } else {
            exec($app_dir."/console cache:clear --env=prod");
        }
        exec("php $app_dir/../vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php");
        $time_end = microtime(true);
        $execution_time = number_format($time_end - $time_start + 1, 2);//+1 sec for correction

        exec("echo 'wtpt8sspib' | sudo chmod -R 775 $app_dir/../ && echo 'wtpt8sspib' | sudo chown -R vu2003:vu2003 $app_dir/../");
        exec("echo 'wtpt8sspib' | sudo chmod -R 777 $app_dir/logs");
        exec("echo 'wtpt8sspib' | sudo chmod -R 777 $app_dir/cache");

        $output->writeln("Cache cleared in ~".$execution_time." sec ".$app_dir);
    }
}
?>