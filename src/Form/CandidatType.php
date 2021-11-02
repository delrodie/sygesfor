<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('carteScoute')
            ->add('nom')
            ->add('prenoms')
            ->add('dateNaissance')
            ->add('lieuNaissance')
            ->add('fonction')
            ->add('dateEntree')
            ->add('niveauEtude')
            ->add('profession')
            ->add('residence')
            ->add('email')
            ->add('contact')
            ->add('idTransaction')
            ->add('statusPaiement')
            ->add('slug')
            ->add('createAt')
            ->add('updatedAt')
            ->add('sexe')
            ->add('region')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
