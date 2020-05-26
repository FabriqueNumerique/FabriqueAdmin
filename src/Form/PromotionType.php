<?php

namespace App\Form;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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

            ->add('Formation');

        

            
                
        $builder
            ->add('apprenants', EntityType::class,[
                'class' => Apprenant::class,
                'multiple' => true,
                'required' => false,
                
                 'query_builder' => function (ApprenantRepository $er) {
                    return $er
                    ->createQueryBuilder('a')
                    ->join('a.Promotion', 'c');
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
