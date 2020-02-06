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
            ->add("title", TextType::class)
            ->add("description", TextareaType::class)
            ->add("profilRequired", TextareaType::class)
            ->add("location", TextType::class)
            ->add("experience", ChoiceType::class, [
                "choices" => [
                    "Tous" => "Tous",
                    "Junior (0 à 2 ans)" => "Junior (0 à 2 ans)",
                    "Confirmé (3 à 6 ans)" => "Confirmé (3 à 6 ans)",
                    "Senior (7 ans et plus)" => "Senior (7 ans et plus)",
                ],
            ])
            ->add("salary", TextType::class)
            ->add("type", ChoiceType::class, [
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
