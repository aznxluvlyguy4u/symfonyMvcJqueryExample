<?php

namespace AppBundle\Form;

use AppBundle\Entity\Card;
use AppBundle\Entity\Company;
use AppBundle\Entity\MembershipStatus;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Membership;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Entity\Document;

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
        /** @var Membership $membership */
        $membership = $options['data'];
        $builder
            ->add('startDate', DateType::class, ['label' =>false, 'data' => $membership->getStartDate(), 'attr' => array('style' => 'display:none')])
            ->add('endDate', DateType::class, ['label' =>false, 'data' => $membership->getEndDate(), 'attr' => array('style' => 'display:none')])
            ->add('company', EntityType::class, ['label' =>false, 'class' => Company::class, 'data' => $membership->getCompany(), 'choice_label' => 'companyName', 'attr' => array('style' => 'display:none')])
            ->add('user', EntityType::class, ['label' =>false, 'class' => User::class, 'data' => $membership->getUser(), 'choice_label' => 'usernameCanonical', 'attr' => array('style' => 'display:none')])
            ->add('card', EntityType::class, ['label' =>false, 'class' => Card::class, 'data' => $membership->getCard(), 'choice_label' => 'id', 'attr' => array('style' => 'display:none')])
            ->add('status', EntityType::class, ['label' =>false, 'class' => MembershipStatus::class, 'data' => $membership->getStatus(), 'choice_label' => 'label', 'attr' => array('style' => 'display:none')])
            ->add('contractDoc', DocumentType::class, [ 'data_class' => Document::class, 'label' => 'Signed contract', 'required' => false])
            ->add('sepaForm', DocumentType::class, [ 'data_class' => Document::class, 'label' => 'SEPA form', 'required' => false])
            ->add('keysForm', DocumentType::class, [ 'data_class' => Document::class, 'label' => 'Keys form', 'required' => false])
            ->add('kvkExtract', DocumentType::class, [ 'data_class' => Document::class, 'label' => 'KVK extract', 'required' => false])
            ->add('depositReceipt',  DocumentType::class, [ 'data_class' => Document::class, 'label' => 'Deposit receipt', 'required' => false])
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
