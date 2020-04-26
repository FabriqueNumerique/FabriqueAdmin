<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('Nom')
            ->add('Prenom')
            ->add('Email')
            ->add('Tel')
            ->add('DateNaissance', DateType::class, [
                    'widget'=> 'single_text'
                ])
            ->add('Adresse')
            ->add('Ville')
            ->add('Git')
            ->add('Avatar')
            // ->add('Reseaux', CollectionType::class, array(
            //         'entry_type' => ReseauxType::class,
            //         'entry_options' => ['label' => false],
            //         'allow_add' => true,
            //         'allow_delete' => true,
            //         'prototype' => true,
            //         'by_reference' => false,
            //         'label'=>'Réseaux Sociaux'
            //     ))
            ;


                
            // ->add('Reseaux', CollectionType::class, [
            //     'entry_type' => ReseauxType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            // ]);
            
                // 'Promotion'
                // ,EntityType::class, array
                //     (
                //     'label' => 'Choisir une promotion',
                //     'class'=>Promotion::class,
                //     'mapped'=>false
                //     )
            // )
            // ;
            // ->add('offre')
            // ->add('reseaux', CollectionType::class,
            // [
            //     'entry_type'=>ReseauxType::class,
            //     // 'allow_add' => true,
            //     // 'allow_delete' => true
            //     ]
            // )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Apprenant::class,
        ]);
    }
}
