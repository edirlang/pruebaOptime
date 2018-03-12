<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, [
                'label' => 'Codigo',
                'attr' => ['class' => 'form-control']
            ])
            ->add('name', null, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', null, [
                'label' => 'Descripción',
                'attr' => ['class' => 'form-control']
            ])
            ->add('active', null, [
                'label' => 'Activo',
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => ['class' => 'form-check-input']
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
