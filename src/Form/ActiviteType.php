<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('code')
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control']])
            ->add('description')
            ->add('debutActivite', TextType::class,['attr'=>['data-toggle'=>"datepicker"]])
            ->add('finActivite', TextType::class,['attr'=>['data-toggle'=>"datepicker"]])
            ->add('debutPeriode', TextType::class,['attr'=>['data-toggle'=>"datepicker"]])
            ->add('finPeriode', TextType::class,['attr'=>['data-toggle'=>"datepicker"]])
            ->add('montant')
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
