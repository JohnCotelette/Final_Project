<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                "label" => "Intitulé du poste*",
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description*",
            ])
            ->add("profilRequired", TextareaType::class, [
                "label" => "Profil requis*",
            ])
            ->add("location", TextType::class, [
                "label" => "Localisation",
            ])
            ->add("experience", ChoiceType::class, [
                "label" => "Experience requise*",
                "placeholder" => "Choisissez une valeur",
                "choices" => [
                    "Tous" => "Tous",
                    "Junior (0 à 2 ans)" => "Junior (0 à 2 ans)",
                    "Confirmé (3 à 6 ans)" => "Confirmé (3 à 6 ans)",
                    "Senior (7 ans et plus)" => "Senior (7 ans et plus)",
                ],
            ])
            ->add("salary", TextType::class, [
                "label" => "Rémunération",
                "data" => 0,
            ])
            ->add("type", ChoiceType::class, [
                "label" => "Type de contrat*",
                "placeholder" => "Choisissez une valeur",
                "choices" => [
                    "CDI" => "CDI",
                    "CDD" => "CDD",
                    "Stage" => "Stage",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Offer::class,
            "csrf_protection" => true,
            "attr" => [
                "novalidate" => "novalidate",
            ],
        ]);
    }
}
