<?php

namespace App\Form;

use App\Entity\Offres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Intitule',ChoiceType::class,[
                
                'choices' => [
                    "" => '',
                    'Stage' => 'Stage',
                    'Contrat' => 'Contrat CDD',
                   
                ]
            ])
            ->add('DateDebut', DateType::class, [
                'widget' => 'single_text',
                
            ])
            ->add('DateFin', DateType::class, [
                'widget' => 'single_text',
                
            ])
            ->add('CahierDesCharges',FileType::class,[
            'data_class'=>null
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
