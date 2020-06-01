<?php

namespace App\Form;

use App\Entity\Retard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetardEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('nombreHeure', TypeIntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 4
                ],
                'label' => "Nombre d'heures"
            ])
            ->add('justifie', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Justifié' => 'oui',
                    'Non Justifié' => 'no'
                ],
                'label' => 'Justifié oui ou non ?'

            ])
            ->add('promoAppre', TextType::class, [
                'disabled' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Retard::class,
        ]);
    }
}
