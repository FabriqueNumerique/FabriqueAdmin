<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\Apprenant;
use App\Entity\PromoAppre;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use App\Repository\PromoAppreRepository;
use App\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceType extends AbstractType
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
            ->add('promoAppre', EntityType::class, [
                'class' => PromoAppre::class,
                'label'=> "Selectiooner l'apprenant",
                // 'mapped'=>false,
                'query_builder' => function (PromoAppreRepository $repo) {
                    return $repo->createQueryBuilder('p')
                        ->join('p.promotion','pro')
                        ->where('pro.DateFin > :date')
                        ->setParameter('date',new \DateTime)
                    ;
                                   
                },
               
            ]);


        // $builder->get('promotion')->addEventListener(
        //      FormEvents::POST_SUBMIT,
        //      function (FormEvent $event) {
                 
        //         $form = $event->getForm();

        //         $form->getPromoAppres[]->add('apprenant', EntityType::class, [
        //             'class' => Apprenant::class,
        //             'choices' => $form->getData()->getApprenants()

        //         ]);
        //     }
        // );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
