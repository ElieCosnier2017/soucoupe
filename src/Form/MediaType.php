<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['fields'] == "update"){
            $builder
                ->add('name', TextType::class, array('attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name')))
                ->add('description',TextType::class, array('attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'ici votre description ...')))
                ->add('genre', null, array('attr' => array(
                    'class' => 'form-control'
                )));
        } else {
            $builder
                ->add('name', TextType::class, array('attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name')))
                ->add('description',TextType::class, array('attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'ici votre description ...')))
                ->add('dateCreated', DateTimeType::class, array(
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control')
                ))
                ->add('picture', FileType::class)
                ->add('genre', null, array('attr' => array(
                    'class' => 'form-control'
                )));
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'fields' => false
        ]);
    }
}
