<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('utilisateur', UtilisateurType::class, [
                'label' => false,
            ])
            ->add('departement', ChoiceType::class, [
                'label' => 'Département',
                'choices' => [
                    'G.USER' => 'G.USER',
                    'G.VOITURE' => 'G.VOITURE',
                    'G.BORNES' => 'G.BORNES',
                    'G.S-A-L' => 'G.S-A-L',
                    'G.EVENEMENTS' => 'G.EVENEMENTS',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
