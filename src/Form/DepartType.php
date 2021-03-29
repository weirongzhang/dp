<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, $options)
    {
        $builder
            ->add('department', EntityType::class, [
                'class' => Department::class
            ])
            ->add('Search', ButtonType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}
