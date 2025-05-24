<?php

namespace App\Form;

use App\Entity\Auxiliar;
use App\Entity\ConstantesVitales;
use App\Entity\Dieta;
use App\Entity\Paciente;
use App\Entity\Registro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reg_timestamp', null, [
                'widget' => 'single_text',
            ])
            ->add('aux_id', EntityType::class, [
                'class' => Auxiliar::class,
                'choice_label' => 'id',
            ])
            ->add('pac_id', EntityType::class, [
                'class' => Paciente::class,
                'choice_label' => 'id',
            ])
            ->add('cv_id', EntityType::class, [
                'class' => ConstantesVitales::class,
                'choice_label' => 'id',
            ])
            ->add('die_id', EntityType::class, [
                'class' => Dieta::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registro::class,
        ]);
    }
}
