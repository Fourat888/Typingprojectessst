<?php

namespace App\Form;

use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                    'placeholder'=>'nom'

                ]
            ])
            ->add('pseudo',TextType::class,
                [  'attr'=>[
                    'placeholder'=>'Pseudo'
                ]])
            ->add('email',EmailType::class,
                [  'attr'=>[
                'placeholder'=>'email'
                ]])
            ->add('Emplacement',HiddenType::class,[
                'attr'=>['name'=>'emplacement',
                    'id'=>'emplacement']])
            ->add('password',PasswordType::class,[
                'attr'=>[
                    'placeholder'=>'password'

                ]
            ])
            ->add('password1',PasswordType::class,[
                'attr'=>[
                    'placeholder'=>'retype password'

                ]
            ])



            ->add('captcha',CaptchaType::class,[

            ])
            ->add('image', FileType::class, array('data_class' => null),[    'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,


            ])
            ->add('lng',HiddenType::class,[
                'attr'=>['name'=>'lng',
                    'id'=>'lng']])
            ->add('lat',HiddenType::class,[
                'attr'=>['name'=>'lat',
                    'id'=>'lat']])
            ->add('Country',HiddenType::class,[
                'attr'=>['name'=>'Country',
                    'id'=>'Country']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
