<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EditCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('status', EntityType::class, ['class' => 'AppBundle:CompanyStatus', 'choice_label' => 'label'])
            ->add('save', SubmitType::class)
            ->add('saveAndQuit', SubmitType::class)
        ;
    }
}
