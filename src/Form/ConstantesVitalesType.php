<?php

namespace App\Form;

use App\Entity\ConstantesVitales;
use App\Entity\Registro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstantesVitalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cv_ta_sistolica')
            ->add('cv_ta_diastolica')
            ->add('cv_frequencia_respiratoria')
            ->add('cv_pulso')
            ->add('cv_temperatura')
            ->add('cv_saturacion_oxigeno')
            ->add('cv_talla')
            ->add('cv_diuresis')
            ->add('cv_deposiciones')
            ->add('cv_stp')
            ->add('cv_timestamp', null, [
                'widget' => 'single_text',
            ])
            ->add('registro', EntityType::class, [
                'class' => Registro::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConstantesVitales::class,
        ]);
    }
}
