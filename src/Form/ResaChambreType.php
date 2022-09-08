<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ResaChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_arrivee', DateTimeType::class, [
            'widget' => 'single_text'
        ])
        ->add('date_depart', DateTimeType::class, [
            'widget' => 'single_text',
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d H:i'),
            ]
        ])
            // ->add('prix_total')
            // ->add('prenom')
            // ->add('nom')
            ->add('telephone')
            // ->add('email')
            // ->add('date_enregistrement')
            // ->add('chambre')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
