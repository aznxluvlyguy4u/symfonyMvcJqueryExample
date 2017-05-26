<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\Card;
use AppBundle\Entity\MembershipStatus;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DateTime;

class MembershipType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Membership::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, ['data' => new DateTime()])
            ->add('endDate')
            ->add('company', EntityType::class, ['class' => Company::class, 'choice_label' => 'companyName'])
            ->add('user', EntityType::class, ['class' => User::class, 'choice_label' => 'usernameCanonical'])
            ->add('card', EntityType::class, ['class' => Card::class, 'choice_label' => 'id'])
            ->add('status', EntityType::class, ['class' => MembershipStatus::class, 'choice_label' => 'label'])
            ->add('save', SubmitType::class)
            ->add('saveAndQuit', SubmitType::class)
        ;
    }

    public function getBlockPrefix()
    {
        return 'appbundle_membership';
    }
}
