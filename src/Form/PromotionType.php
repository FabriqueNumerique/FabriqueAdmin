<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PromotionType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Annee', IntegerType::class, [
                'attr' => array('min' => 2018, 'max' => 2030)

                ])

            ->add('DateDebut', DateType::class, [
                'widget' => 'single_text'
                ])

            ->add('DateFin', DateType::class, [
                'widget' => 'single_text',
                ])

            ->add('Commentaires')

            ->add('Formation', EntityType::class,[
                'class'=>Formation::class,
                'required'=>true,
                'label'=>'Selectionner la formation'
            ]) 

            ->add('apprenants',EntityType::class,[
                'class'=>Apprenant::class,
                'multiple' => true,
                'required' => false,
                'mapped'=>false,
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
            'data_class' => Promotion::class,
        ]);
    }
}
