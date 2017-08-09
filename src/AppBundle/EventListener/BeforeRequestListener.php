<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BeforeRequestListener
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function onKernelRequest(GetResponseEvent $event)
    {
        $filter = $this->em
            ->getFilters()
            // Disabled, needs to be rewritten in the Respective Entity Repositories
            // ->enable('is_deleted_filter')
        ;
    }
}
