<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\CompanyStatus;

class LoadCompanyStatus implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listOfStatuses = ['suspect', 'lead', 'approval', 'member', 'canceled', 'denied'];
        $fixtureObjects = [];

        foreach ($listOfStatuses as $status) {
            $iterativeStatus = new CompanyStatus();
            $iterativeStatus->setLabel($status);
            $fixtureObjects[] = $iterativeStatus;
        }

        foreach ($fixtureObjects as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
