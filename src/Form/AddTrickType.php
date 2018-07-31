<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('family')
            ->add('listingPicture') // DONT WORK SEE https://www.developpez.net/forums/d1309462/php/bibliotheques-frameworks/symfony/probleme-lier-plusieurs-images-article-onetomany/
        ;
    }

    //TODO: Essayer de lier listing picture a l'entité picture: Faire un formulaire d'ajout d'image simple et l'inclure dans ce formulaire....

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
