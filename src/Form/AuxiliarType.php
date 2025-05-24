<?php

namespace App\Form;

use App\Entity\Auxiliar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuxiliarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aux_num_trabajador')
            ->add('aux_nombre')
            ->add('aux_apellidos')
            ->add('aux_password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auxiliar::class,
        ]);
    }
}
