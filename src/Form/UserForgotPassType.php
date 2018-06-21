<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 17/06/2018
 * Time: 11:49
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForgotPassType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label'    => "Nom d'utilisateur",
                'required' => true,
                'constraints' => [new NotBlank()],
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_forgotPassCSRF',
            'csrf_token_id'   => 'forgotPassCSRF',
        ]);
    }
}