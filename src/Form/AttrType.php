<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Promotion',EntityType::class,[
                'class'=>Promotion::class
            ])
            ->add('Apprenant',EntityType::class,[
                'class'=>Apprenant::class,
                // on affiche seulement les apprenants dont le statut est "new"
                'query_builder' => function (ApprenantRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.status = :status')
                        ->setParameter('status', 'new')
                        ->orderBy('u.Nom');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
