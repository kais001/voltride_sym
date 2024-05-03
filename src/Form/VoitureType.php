<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('etat')
            ->add('prixLocation')
            ->add('kilometrage')
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, JPEG, PNG file)',
                'mapped' => false, // Ne pas mapper à une propriété de l'entité
                'required' => true,
                'attr' => [
                    'accept' => 'image/jpeg, image/png', // Types de fichiers autorisés
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
