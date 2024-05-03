<?php

namespace App\Form;

use App\Entity\Borne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emplacement')
            ->add('capacite')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Indisponible' => 'Indisponible',
                ],
                'placeholder' => 'Choose an option',
            ])
            ->add('dateInst');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borne::class,
            'csrf_protection' => true,
        ]);
    }
}
