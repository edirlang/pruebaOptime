<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, [
                'label' => 'Codigo',
                'attr' => ['class' => 'form-control'],
                'invalid_message' => 'El codigo no es valido',
            ])
            ->add('name', null, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control'],
                'invalid_message' => 'El nombre no es valido',
            ])
            ->add('description', null, [
                'label' => 'Descripción',
                'attr' => ['class' => 'form-control'],
                'invalid_message' => 'La descripción no es validad',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Precio',
                'attr' => ['class' => 'form-control'],
                'invalid_message' => 'El precio no es un numero valido',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categoria',
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
