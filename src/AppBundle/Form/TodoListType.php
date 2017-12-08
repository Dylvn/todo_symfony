<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TodoListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre',
                'required' => true,
            ))
            ->add('checked', CheckboxType::class, array(
                'label' => 'Fait ?',
                'required' => false,
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Créer',
            ))
            ->add('update', SubmitType::class, array(
                'label' => 'Modifier',
            ))
            ->add('delete', SubmitType::class, array(
                'label' => 'Supprimer',
                'attr' => array('class' => 'btn btn-danger'),
            ))
        ;
    }

}
