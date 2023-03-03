<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DrawType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, [
                'label'         => 'Nombre de cartes à tirer',
                'constraints'   => new Assert\Range(['min' => 1, 'max' => 52, 'notInRangeMessage' => 'Le nombre de cartes doit etre compris entre 1 et 52']),
                'attr'          => [
                    'size' => 2
                ]
            ])->add('submit', SubmitType::class, [
                'label'         => 'Piocher'
            ]);
    }
}