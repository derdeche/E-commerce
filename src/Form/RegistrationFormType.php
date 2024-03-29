<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'constraints' => [new NotBlank()],
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Pseudo',
                    'class' => 'input',
                     
                ],
               
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank(), new Email()],
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Email',
                    'class' => 'input',
                     
                ],
                
               
            ]);
            
            
        $builder    
            ->add('plainPassword', RepeatedType::class, [              
                'type'=> PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'votre mot de passe doit avoir au moin {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/',
                     "Le mot de passe doit contenir au moin une majuscule, une minuscule, un chiffre et un caractère spécial.")
                ],
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                        'class' => 'input',
                    ],
                ],
                'second_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                        'class' => 'input1',
                    ],
                ],
                
            ])
            
            ->add('agreeTerms',   CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions d\'utilisation.',
                    ]),
                ],
                
                'row_attr' => [
                    'class' => 'check1',
                ],
                'label' => 'J\'accepte les conditions d\'utilisation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'email_exists' => false,
        ]);
    }
}
