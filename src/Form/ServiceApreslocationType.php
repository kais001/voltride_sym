<?php

namespace App\Form;

use App\Entity\ServiceApreslocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ServiceApreslocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('technicien', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-z]+$/',
                        'message' => 'Ce champ doit contenir uniquement des lettres.',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-z0-9\s]+$/',
                        'message' => 'La description doit contenir uniquement des lettres, des chiffres et des espaces.',
                    ]),
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'disponible',
                    'Non disponible' => 'non disponible',
                    'Bientôt disponible' => 'bientôt disponible',
                ],
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['disponible', 'non disponible', 'bientôt disponible'],
                        'message' => 'Le statut doit être "disponible", "non disponible" ou "bientôt disponible".',
                    ]),
                ],
            ])
            ->add('cout', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le coût doit contenir uniquement des chiffres.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceApreslocation::class,
        ]);
    }
}
