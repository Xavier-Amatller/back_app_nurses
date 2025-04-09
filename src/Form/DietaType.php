<?php

namespace App\Form;

use App\Entity\Dieta;
use App\Entity\Registro;
use App\Entity\TiposDieta;
use App\Entity\TiposTexturas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DietaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Die_Autonomo')
            ->add('Die_Protesi')
            ->add('registro', EntityType::class, [
                'class' => Registro::class,
                'choice_label' => 'id',
            ])
            ->add('Die_TText', EntityType::class, [
                'class' => TiposTexturas::class,
                'choice_label' => 'id',
            ])
            ->add('Tipos_Dietas', EntityType::class, [
                'class' => TiposDieta::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dieta::class,
        ]);
    }
}
