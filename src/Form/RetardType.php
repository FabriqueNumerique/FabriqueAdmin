<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Retard;
use App\Repository\ApprenantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetardType extends AbstractType
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
                ]
            ])
            ->add('justifie',ChoiceType::class,[
                'choices' => [
                    'Justifié' => 'oui',
                    'Non Justifié' => 'no'
                ]
            ])
            ->add('apprenant',EntityType::class,[
                'class'=>Apprenant::class,
            'query_builder' => function (ApprenantRepository $er) {
                return $er->createQueryBuilder('a')
                    ->join('a.Promotion','p')
                    ->where('p.DateFin >= :date')
                    ->setParameter('date', new \DateTime)
                    ->orderBy('a.Nom');
            }
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
