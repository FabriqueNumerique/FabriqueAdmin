<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Promotion')
            // ->add('Promotion', EntityType::class,[
            //     'class' => Promotion::class,
            //     'query_builder' => function (PromotionRepository $er) {
            //         return $er->createQueryBuilder('u')
            //         ->orderBy('u.Annee', 'ASC');
            //     },
            // ])
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
            // ->add('Avatar')
            ->add('brochure', FileType::class, [
                'label' => 'Avatar ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        // 'mimeTypes' => [
                        //     'application/pdf',
                        //     'application/x-pdf',
                        // ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            // ->add('Reseaux', CollectionType::class, array(
            //         'entry_type' => ReseauxType::class,
            //         // 'entry_options' => ['label' => false],
            //         'allow_add' => true,
            //         // 'allow_delete' => true,
            //         // 'prototype' => true,
            //         // 'by_reference' => false,
            //         // 'label'=>'Réseaux Sociaux'
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
