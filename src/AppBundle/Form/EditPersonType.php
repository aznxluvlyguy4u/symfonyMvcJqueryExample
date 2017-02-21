<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class EditPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('passNumber')
            ->add('companies', EntityType::class, [
                'class' => 'AppBundle:Company',
                'query_builder' => function (EntityRepository $er) {
                    return $er->queryAll();
                },
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('save', SubmitType::class)
        ;
    }
}
