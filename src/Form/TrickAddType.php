<?php

namespace App\Form;

use App\Entity\Family;
use App\Entity\Trick;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la figure'
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'config' => ['toolbar' => 'standard']
            ])
            ->add('family', EntityType::class, [
                'label' => 'Famille',
                'class' => Family::class,
                'choice_label' => 'title'
            ])


            ->add('pictures', FileType::class, [
                'label' => 'Image(s)',
                'required' => false,
                'multiple' => true
            ])


            ->add('videos', CollectionType::class, [
                'label' => 'Videos',
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'validation_groups' => 'newtrick',
        ]);
    }
}
