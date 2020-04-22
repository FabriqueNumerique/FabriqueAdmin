<?php

namespace App\Form;

use App\Entity\Apprenant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Email')
            ->add('Tel')
            ->add('DateNaissance', DateType::class, array('years' => range(1970, 2020, 1), 'format' => 'dd-MM-yyyy'))
            ->add('Adresse')
            ->add('Ville')
            ->add('Git')
            ->add('Avatar')
            // ->add('Promotion')
            // ->add('offre')
            ->add('reseaux', CollectionType::class,
            [
                'entry_type'=>ReseauxType::class,
                // 'allow_add' => true,
                // 'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Apprenant::class,
        ]);
    }
}
