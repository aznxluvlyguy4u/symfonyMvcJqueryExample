<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use AppBundle\Service\AWSSimpleStorageService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3DocumentUploader
{
    private $s3service;
    private $tokenStorage;

    public function __construct(AWSSimpleStorageService $s3service, TokenStorage $tokenStorage)
    {
        $this->s3service = $s3service;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function postLoad($document, LifecycleEventArgs $args)
    {
        $presignedUrl = $this->s3service->getPresignedUrl($document->getS3Key());
        if ($presignedUrl) {
            $document->setPresignedUrl($presignedUrl);
        }
    }

    public function prePersist($document, LifecycleEventArgs $args)
    {
        $loggedInUser = $this->tokenStorage->getToken()->getUser();
        $document->setCreatedBy($loggedInUser);
        $uploadedFile = $document->getFile();

        if ($uploadedFile && $uploadedFile instanceof UploadedFile) {
            $filename = $uploadedFile->getClientOriginalName();
            $size = $uploadedFile->getSize();
            $s3Key = md5(uniqid()).'-'.$filename;
            $mimeType = $uploadedFile->getMimeType();
            $presignedUrl = $this->s3service->uploadFromFilePath($uploadedFile, $s3Key, $mimeType);
            if ($presignedUrl) {
                $document->setPresignedUrl($presignedUrl);
            }
            $document->setS3Key($s3Key);
            $document->setSize($size);
            $document->setMimeType($mimeType);
            $document->setFilename($filename);
        }
    }
}