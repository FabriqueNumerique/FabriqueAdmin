<?php

namespace App\Form;

use App\Entity\Reseau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReseauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class,[
                'attr'=>[
                    'placeholder'=>'saisissez le nom du réseau'
                ]
            ])
            ->add('Lien', TextType::class, [
                'attr' => [
                    'placeholder' => 'saisissez le lien du réseau'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reseau::class,
        ]);
    }
}
