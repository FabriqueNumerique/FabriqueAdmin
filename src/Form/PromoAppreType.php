<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\PromoAppre;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use App\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoAppreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Promotion', EntityType::class, [
                'class' => Promotion::class,
                'label' => 'Selectionner une promotion',
                'query_builder' => function (PromotionRepository $repo) {
                    return $repo->createQueryBuilder('p')
                        ->where('p.DateFin > :date')
                        ->setParameter('date', new \DateTime)
                        ->OrderBy('p.DateFin', 'DESC');
                }
            ])
            ->add('apprenant', EntityType::class, [
                'class' => Apprenant::class,
                // 'multiple' => true,
                // 'required' => false,
                'label' => 'Attribuer des apprenants',
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
            'data_class' => PromoAppre::class,
        ]);
    }
}
