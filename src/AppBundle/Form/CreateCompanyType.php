<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanySector;
use AppBundle\Entity\CompanyStatus;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCompanyType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Company::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactFirstname', TextType::class, array('label' => 'First Name'))
            ->add('contactLastname', TextType::class, array('label' => 'Last Name'))
            ->add('companyName')
            ->add('numberOfEmployees')
            ->add('squareMetersWanted')
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('websiteUrl')
            ->add('reference')
            ->add('offer')
            ->add('demand')
            ->add('sector', EntityType::class, ['class' => CompanySector::class, 'choice_label' => 'label'])
            ->add('status', EntityType::class, ['class' => CompanyStatus::class, 'choice_label' => 'label'])
            ->add('reference', TextType::class, array('label' => 'How did you find us?'))
            ->add('offer', TextType::class, array('label' => 'What do you bring on the table?'))
            ->add('demand', TextType::class, array('label' => 'What do you want to get?'))
            ->add('save', SubmitType::class)
            ->add('saveAndQuit', SubmitType::class)
        ;
    }
}
