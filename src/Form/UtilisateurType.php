<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Lastname')))
            ->add('firstname', TextType::class, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Firstname')))
            ->add('email', EmailType::class, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Email')))
            ->add('username', TextType::class, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Username')))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Password')),
                'second_options' => array('label' => 'Repeat Password',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Repeat Password')),
                'invalid_message' => 'Your passwords do not match!'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
