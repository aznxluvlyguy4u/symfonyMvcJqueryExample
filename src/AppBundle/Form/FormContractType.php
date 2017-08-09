<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;

class FormContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', EntityType::class, [
                'class' => 'AppBundle:Company',
                'query_builder' => function (EntityRepository $er) {
                    return $er->queryAll();
                },
                'choice_label' => 'companyName',
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => 'false',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
            ])
            ->add('save', SubmitType::class)
            ->add('saveAndQuit', SubmitType::class)
        ;
    }
}
