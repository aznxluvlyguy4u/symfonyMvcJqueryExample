<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SendEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('to', EmailType::class, array('label' => 'Recipient'))
            ->add('subject', TextType::class)
            ->add('body', TextareaType::class, array('label' => 'Message', 'attr' => array('rows' => '8')))
            ->add('close', ButtonType::class, array('attr' => array('class' => 'btn btn-default', 'data-dismiss' => 'modal')))
            ->add('sendMessage', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')))
        ;
    }
}
