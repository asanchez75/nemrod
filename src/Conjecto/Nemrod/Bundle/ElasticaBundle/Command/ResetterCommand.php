<?php

/*
 * This file is part of the Nemrod package.
 *
 * (c) Conjecto <contact@conjecto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Conjecto\Nemrod\Bundle\ElasticaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('nemrod:elastica:reset')
            ->setDescription('Resetting elastica types/indexes')
            ->addOption('index', null, InputOption::VALUE_OPTIONAL, 'The index to reset')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'The type to reset')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force index deletion if same name as alias');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $index = $input->getOption('index');
        $type = $input->getOption('type');
        $force = (bool) $input->getOption('force');

        if (null !== $type) {
            $output->writeln(sprintf('<info>Resetting</info> <comment>%s/%s</comment>', $index, $type));
            $this->getContainer()->get('nemrod.elastica.resetter')->reset($type, $output);
        } else {
            $indexes = null === $index
                ? array_keys($this->getContainer()->get('nemrod.elastica.config_manager')->getIndexes())
                : array($index)
            ;
            foreach ($indexes as $index) {
                $output->writeln(sprintf('<info>Resetting</info> <comment>%s</comment>', $index));
                $this->getContainer()->get('nemrod.elastica.resetter')->resetIndex($index, false, $force);
            }
        }


    }
}
