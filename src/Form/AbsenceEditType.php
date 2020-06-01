<?php

namespace App\Form;

use App\Entity\Absence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('justifie', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Justifié' => 'oui',
                    'Non Justifié' => 'no'
                ],
                'label' => 'Justifié oui ou non ?'

            ])
            ->add('promoAppre',TextType::class,[
                'disabled'=>true
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
