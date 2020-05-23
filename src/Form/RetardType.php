<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Entity\Retard;

use App\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                ],
                'label' => "Nombre d'heures"
            ])
            ->add('justifie', ChoiceType::class, [
                'choices' => [
                    ''=>'',
                    'Justifié' => 'oui',
                    'Non Justifié' => 'no'
                ],
                'label' => 'Justifié oui ou non ?'
                
            ])
            ->add('promotion', EntityType::class, [
                'class' => Promotion::class,
                'query_builder' => function (PromotionRepository $repo) {
                    return $repo->createQueryBuilder('p')
                        ->where('p.DateFin > :date')
                        ->setParameter('date', new \DateTime);
                },
                'label' => 'Selectionner une promotion'
            ]);
            
        $builder->get('promotion')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                
                $form->getParent()->add('apprenant', EntityType::class, [
                    'class' => Apprenant::class,
                    'choices' => $form->getData()->getApprenants()
                   
                ]);
            }
        );

        
        
       
    }

   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Retard::class,
        ]);
    }
}
