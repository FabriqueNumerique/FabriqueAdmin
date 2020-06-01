<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Offres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Entreprise',EntityType::class,[
                'class' => Entreprise::class
            ])
            ->add('Intitule',ChoiceType::class,[
                
                'choices' => [
                    'Stage' => 'stage',
                    'Projet'=> 'projet',
                    'Contrat CDD' => 'contrat CDD',
                    'Contrat CDI' => 'contrat CDI'
                   
                ]
            ])
            ->add('DateDebut', DateType::class, [
                'widget' => 'single_text',
                
            ])
            ->add('DateFin', DateType::class, [
                'widget' => 'single_text',
                
            ])
            ->add('brochure', FileType::class, [
                'label' => 'Cahier des charges ',
                'mapped' => false,
                'attr' => [
                    'opacity' => 1
                ],

                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        // 'mimeTypes' => [
                        //     'application/pdf',
                        //     'application/x-pdf',
                        // ],
                        // 'mimeTypesMessage' => "S'il vous plaît, téléversez une image valide",
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
