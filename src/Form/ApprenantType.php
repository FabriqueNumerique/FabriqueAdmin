<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
           
            ->add('brochure', FileType::class, [
                'label' => 'Avatar ',
                'mapped' => false,
                'attr'=>[
                    'opacity'=>1
                ],

                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        // 'mimeTypes' => [
                        //     'application/pdf',
                        //     'application/x-pdf',
                        // ],
                        'mimeTypesMessage' => "S'il vous plaît, téléversez une image valide",
                    ])
                ],
            ])

            // ->add('Promotion',EntityType::class,[
            //     'class' => Promotion::class,
            //     'mapped' => false,
            //     'required' => false
            // ])
            
            ->add('Reseaux', CollectionType::class, array(
                    'entry_type' => ReseauxType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
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
