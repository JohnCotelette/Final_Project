<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passe saisis ne correspondent pas",
                "label" => "Password",
                "required" => true,
                "constraints" => [
                    new Length([
                        "min" => 8,
                        "max" => 30,
                        "minMessage" => "Votre mot de passe doit faire au minimum 8 caractères",
                        "maxMessage" => "Votre mot de passe ne peut dépasser 30 caractères",
                    ]),
                    new NotBlank(),
                    new Regex([
                        "pattern" => "/^\S+$/",
                        "message" => "N'utilisez pas d'espace dans votre mot de passe"
                    ])
                ],
                "options" => ["attr" => ["class" => "password-field"]],
                "first_options" => ["label" => "Entrez votre nouveau mot de passe"],
                "second_options" => ["label" => "Entrez le de nouveau"],
            ])
            ->add("save", SubmitType::class, [
                "label" => "Confirmer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "csrf_protection" => true
        ]);
    }
}