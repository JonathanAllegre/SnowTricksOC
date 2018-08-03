<?php

namespace App\Form;

use App\Entity\Family;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('family', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'title'
            ])
            ->add('image', FileType::class, ['data_class' => null, 'required' => false,])
            ->add('video', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create TRick'))
        ;
    }
}
