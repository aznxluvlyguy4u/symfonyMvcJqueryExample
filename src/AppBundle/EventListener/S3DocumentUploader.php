<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Service\AWSSimpleStorageService;
use AppBundle\Entity\Document;

class S3DocumentUploader
{
    private $s3service;

    public function __construct(AWSSimpleStorageService $s3service)
    {
        $this->s3service = $s3service;
    }

    public function preFlush(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Document) {
            return;
        }

        dump($entity);die();
        $entityManager = $args->getEntityManager();
    }
}