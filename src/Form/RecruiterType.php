<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\Constraints\ExistAndUniqueSiret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RecruiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", EmailType::class, [
                "attr" => [
                    "placeholder" => "Email",
                ],
            ])
            ->add("password", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passe ne correspondent pas",
                "options" => [
                    "attr" => [
                        "class" => "password-field",
                    ],
                ],
                "required" => true,
                "first_options"  => [
                    "label" => false,
                    "attr" => [
                        "placeholder" => "Mot de passe",
                    ],
                ],
                "second_options" => [
                    "label" => false,
                    "attr" => [
                        "placeholder" => "Confirmation de mot de passe",
                    ],
                ],
                "constraints" => [
                    new Regex([
                        "pattern" => "/^\S+$/",
                        "message" => "N'utilisez pas d'espace dans votre mot de passe",

                    ]),
                ],
            ])
            ->add("firstName", TextType::class, [
                "attr" => [
                    "placeholder" => "Prénom",
                ],
            ])
            ->add("lastName", TextType::class, [
                "attr" => [
                    "placeholder" => "Nom",
                ],
            ])
            ->add("birthDay", BirthdayType::class, [
                "label" => "Date de naissance",
                "placeholder" => [
                    "day" => "Jour",
                    "month" => "Mois",
                    "year" => "Année",
                ],
                "years" => range(date('Y') - 65, date('Y') - 17),
            ])
            ->add("business", TextType::class, [
                "label" => "Siret Entreprise",
                "mapped" => false,
                "attr" => [
                    "placeholder" => "Siret Entreprise",
                ],
                "constraints" => [
                    new Regex([
                        "pattern" => "/^\d{14}$/",
                        "message" => "Les numéros de Siret Français sont composés de 14 chiffres",
                    ]),
                    new NotBlank([
                        "message" => "Veuillez renseigner le numéro de Siret de votre entreprise",
                    ]),
                    new ExistAndUniqueSiret(),
                ],
            ])
            ->add("legalConditions", CheckboxType::class, [
                "mapped" => false,
                "required" => true,
                "label" => "En cochant ceci, vous acceptez les conditions générales d'utilisation et vous certifiez être majeur.",
                "constraints" => [
                    new IsTrue([
                        "message" => "Vous devez accepter nos conditions générales d'utilisation si vous souhaitez poursuivre",
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class,
            "csrf_protection" => true,
            "attr" => [
                "novalidate" => "novalidate",
            ],
        ]);
    }
}
