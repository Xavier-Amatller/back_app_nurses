<?php

namespace App\Form;

use App\Entity\Paciente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pac_num_historial')
            ->add('pac_nombre')
            ->add('pac_apellidos')
            ->add('pac_fecha_nacimiento', null, [
                'widget' => 'single_text',
            ])
            ->add('pac_direccion_completa')
            ->add('pac_lengua_materna')
            ->add('pac_antecedentes')
            ->add('pac_alergias')
            ->add('pac_nombre_cuidador')
            ->add('pac_telefono_cuidador')
            ->add('pac_fecha_ingreso', null, [
                'widget' => 'single_text',
            ])
            ->add('pac_timestamp', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paciente::class,
        ]);
    }
}
