<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocumentTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        $data = array(FormEvents::PRE_SET_DATA => 'preSetData');
        return $data;
    }

    public function preSetData(FormEvent $event)
    {
        $document = $event->getData();
        $form = $event->getForm();

        if (!$document || null === $document->getId()) {
            $form
                ->add('file', FileType::class, ['data_class' => null, 'label' => false]);

        } else {
            $isDeleted = $document->getIsDeleted() == true ? 1 : 0;
            $form
                ->add('presignedUrl', HiddenType::class, array('data' => $document->getPresignedUrl()))
                ->add('filename', HiddenType::class, array('data' => $document->getFilename()))
                ->add('mimeType', HiddenType::class, array('data' => $document->getMimeType()))
                ->add('size', HiddenType::class, array('data' => $document->getSize()))
                ->add('s3Key', HiddenType::class, array('data' => $document->getS3Key()))
                ->add('isDeleted', HiddenType::class, array('data' => $isDeleted));
        }
    }
}

