<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('Adresse')
            ->add('CodePostal')
            ->add('Ville')
            ->add('Tel')
            ->add('Mobile')
            ->add('Email')
            ->add('Contact',CollectionType::class,[
                'entry_type'=>ContactType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])
            // ->add('Offres', CollectionType::class, [
            //     'entry_type' => OffreType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'required' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
