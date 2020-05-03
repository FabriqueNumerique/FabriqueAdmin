<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('Promotion')
                // 'class'=>Promotion::class,
                // 'query_builder' => function (PromotionRepository $er) {
                //     return $er->createQueryBuilder('u')
                //         ->where('u.DateFin > :date')
                //         ->setParameter('date', new \DateTime);
                    
                // }
           
            // ->add('Promotion', EntityType::class,[
            //     'class' => Promotion::class,
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
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Nouvel apprenant' => 'new',
                    'Ancien apprenant' => 'old'
                ]
            ])
            ->add('Git')
            // ->add('Avatar')
            ->add('brochure', FileType::class, [
                'label' => 'Avatar ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'attr'=>[
                    'opacity'=>1
                ],

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
            
            ->add('Reseaux', CollectionType::class, array(
                    'entry_type' => ReseauxType::class,
                    // 'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    // 'prototype' => true,
                    // 'by_reference' => false,
                    'label'=>'Réseaux Sociaux',
                    'required' => false
                ))
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Apprenant::class,
        ]);
    }
}
