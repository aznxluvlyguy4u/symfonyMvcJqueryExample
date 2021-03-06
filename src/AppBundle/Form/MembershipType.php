<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Entity\Membership;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\Card;
use AppBundle\Entity\MembershipStatus;
use AppBundle\Entity\Document;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MembershipType extends AbstractType
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
        $defaultStartDate = $options['data']->getStartDate() == null ? new DateTime() : $options['data']->getStartDate();
        $defaultEndDate = $options['data']->getEndDate() == null ? new DateTime() : $options['data']->getEndDate();

        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('emailAddress', EmailType::class)
            ->add('phoneNumber')
            ->add('newsletter', CheckboxType::class, array('label' => 'Newsletter', 'required' => false))
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => 'false',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => 'false',
                'required' => false,
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'companyName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->leftJoin('c.status', 'status')
                        ->where('c.isDeleted = false')
                       // ->andWhere('status.label = :label or status.label = :label2')
                        ->orderBy('c.companyName', 'ASC')
                        //->setParameter('label', 'Contract signed')
                        //->setParameter('label2', 'member')
                    ;

                },
                'required' => true,
                'multiple' => true
            ])
            ->add('card',
                EntityType::class,
                [
                    'class' => Card::class, 'choice_label' => function ($card) {

                    return $card->getNumber() . ' (' . (count($card->getMembership()) ? 'In Use' : 'Available') . ')';
                },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where('c.isDeleted = false')
                            ->andWhere('c.status = true');
                },
                    'required' => false,])
            ->add('status', EntityType::class, ['class' => MembershipStatus::class, 'choice_label' => 'label', 'required' => false])
//            ->add('contractDoc', DocumentTypeorg::class, [ 'data_class' => Document::class, 'label' => 'Signed contract', 'required' => false])
//            ->add('sepaForm', DocumentTypeorg::class, [ 'data_class' => DocumentTypeorg::class, 'label' => 'SEPA form', 'required' => false])
//            ->add('keysForm', DocumentTypeorg::class, [ 'data_class' => DocumentTypeorg::class, 'label' => 'Keys form', 'required' => false])
//            ->add('kvkExtract', DocumentTypeorg::class, [ 'data_class' => DocumentTypeorg::class, 'label' => 'KVK extract', 'required' => false])
//            ->add('depositReceipt',  DocumentTypeorg::class, [ 'data_class' => DocumentTypeorg::class, 'label' => 'Deposit receipt', 'required' => false])
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
