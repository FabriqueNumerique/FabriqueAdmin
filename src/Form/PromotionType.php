<?php

namespace App\Form;

use App\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Annee', IntegerType::class, array(
                'attr' => array('min' => 2018, 'max' => 2030)
            ))
            ->add('DateDebut', DateType::class, array('years' => range(2017, 2050, 1), 'format' => 'dd-MM-yyyy'))
            ->add('DateFin', DateType::class, array('years' => range(2017, 2050, 1), 'format' => 'dd-MM-yyyy'))
            ->add('NombreEleves', IntegerType::class, array(
                'attr' => array('min' => 1)
            ))
            ->add('Commentaires')
            ->add('Formation')
            // ->add('Apprenant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
