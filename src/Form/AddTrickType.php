<?php

namespace App\Form;

use App\Entity\Family;
use App\Entity\Trick;
use App\Repository\FamilyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('family', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'title'
            ])
            ->add('listingPicture', FileType::class, ['data_class' => null, 'required' => false,])
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            // DONT WORK SEE https://www.developpez.net/forums/d1309462/php/bibliotheques-frameworks/symfony/probleme-lier-plusieurs-images-article-onetomany/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
