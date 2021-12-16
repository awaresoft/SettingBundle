<?php

namespace Awaresoft\SettingBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateBuildDatetimeCommand
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class UpdateBuildDatetimeCommand extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('awaresoft:setting:update-build-datetime')
            ->setDescription('Update datetime of latest build')
            ->addOption('date', 'c', InputOption::VALUE_REQUIRED, 'datetime of latest build');
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $settingService = $this->getContainer()->get('awaresoft.setting');

        $datetime = new \DateTime();

        if ($input->getOption('date')) {
            $datetime = new \DateTime($input->getOption('date'));
        }

        $lastBuildSetting = $settingService->getField('WEBSITE', 'LAST_BUILD', true);
        $lastBuildSetting->setValue($datetime->format('Y-m-d H:i:s'));
        $em->flush($lastBuildSetting);
    }
}
