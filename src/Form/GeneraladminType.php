<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeneraladminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                    'placeholder'=>'nom'

                ]
            ])
            ->add('prenom',TextType::class,
                [  'attr'=>[
                    'placeholder'=>'prenom'
                ]])
            ->add('email',EmailType::class,
                [  'attr'=>[
                    'placeholder'=>'email'
                ]])
            ->add('image', FileType::class,
                [
                'mapped' => false,
                'required' => false,
            ])

            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
