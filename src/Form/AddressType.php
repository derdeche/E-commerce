<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('address_type', ChoiceType::class, [
        //     'choices' => [
        //         'Adresse de livraison' => 'livraison',
        //         'Adresse domicile' => 'domicile',
        //     ],
        //     'label' => 'Type d\'adresse',
        //     'expanded' => true, 
        //     'multiple' => false, 
        // ])
            ->add('client_name')
            ->add('street')
            ->add('code_postal')
            ->add('city')
            ->add('state')
            ->add('more_details')
            
             ->add('user_id', HiddenType::class, [
                 'mapped' => false,
             ]);
          
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
