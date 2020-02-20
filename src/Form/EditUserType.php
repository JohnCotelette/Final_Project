<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", TextType::class, [
                "label" => "Prénom",
            ])
            ->add("lastName", TextType::class, [
                "label" => "Nom",
            ])
            ->add("birthDay", BirthdayType::class, [
                "label" => "Date de naissance",
                "placeholder" => [
                    "day" => "Jour",
                    "month" => "Mois",
                    "year" => "Année",
                ],
                "years" => range(date("Y") - 65, date("Y") - 17)
            ])
            ->add("phoneNumber", TextType::class, [
                "label" => "Téléphone",
                "attr" => [
                    "maxlength" => 12,
                ]
            ])
            ->add("webSite", TextType::class, [
                "label" => "Site Web",
                "attr" => [
                    "maxlength" => 180,
                ]
            ]);
    }
   
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class,
            "csrf_protection" => true,
            "attr" => [
                "novalidate" => "novalidate",
                "id" => "formProfile",
            ],
        ]);
    }
}
