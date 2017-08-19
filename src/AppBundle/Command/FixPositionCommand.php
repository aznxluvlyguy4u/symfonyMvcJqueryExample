<?php
// src/AppBundle/Command/CreateUserCommand.php
namespace AppBundle\Command;

use Gedmo\Sortable\SortableListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;


class FixPositionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fix-positions')

            // the short description shown while running "php bin/console list"
            ->setDescription('Fixes the positions of existing records.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command fixes the position of existing records...')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Choice: ',
            array('Company Status', 'Membership Status', 'All'),
            0
        );
        $question->setErrorMessage('Choice %s is invalid.');

        $choice = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: '.$choice);

        switch($choice) {
            case 0: {

                $this->fixPositions('AppBundle:CompanyStatus');
                break;
            }
            case 1: {

                $this->fixPositions('AppBundle:MembershipStatus');
                break;
            }
            case 2: {

                $this->fixPositions('AppBundle:CompanyStatus');
                $this->fixPositions('AppBundle:MembershipStatus');
                break;
            }
        }
    }

    private function fixPositions($repository)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();

        $searchedListener = null;
        $listeners = $em->getEventManager()->getListeners();

        foreach ($listeners as $key => $listener) {
            if ($listener instanceof SortableListener) {
                $searchedListener = $listener;
                break;
            }
        }


        if ($searchedListener) {
            $evm = $em->getEventManager();
            $evm->removeEventListener(array('onFlush'), $searchedListener);
        }

        $entities = $em->getRepository($repository)->findBy(array('isDeleted' => false), array());

        $position = 0;
        foreach($entities as $entity) {
            $entity->setPosition($position);
            $em->persist($entity);
            $position++;
        }

        $em->flush();

    }
}
