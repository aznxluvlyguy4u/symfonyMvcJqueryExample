<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Membership;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MembershipDocumentType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Membership::class,
            'redirect' => 'app_membership_index'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contractDoc', CollectionType::class, ['entry_type' => DocumentType::class, 'allow_add' => true, 'label' => 'Signed Contract', 'required' => false])
            ->add('sepaForm', CollectionType::class, ['entry_type' => DocumentType::class, 'allow_add' => true, 'label' => 'SEPA Form', 'required' => false])
            ->add('keysForm', CollectionType::class, ['entry_type' => DocumentType::class, 'allow_add' => true, 'label' => 'Keys Form', 'required' => false])
            ->add('kvkExtract', CollectionType::class, ['entry_type' => DocumentType::class, 'allow_add' => true, 'label' => 'KVK Extract', 'required' => false])
            ->add('depositReceipt', CollectionType::class, ['entry_type' => DocumentType::class, 'allow_add' => true, 'label' => 'Deposit Receipt', 'required' => false])
            ->add('save', SubmitType::class)
            ->add('saveAndQuit', SubmitType::class)
            ->add('redirect', HiddenType::class, array('data' => $options['redirect'], 'mapped' => false))
        ;
    }

    public function getBlockPrefix()
    {
        return 'appbundle_membership';
    }
}
