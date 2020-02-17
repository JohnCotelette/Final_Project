<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Offer;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                "label" => "Intitulé du poste*",
            ])
            ->add("location", TextType::class, [
                "label" => "Localisation",
            ])
            ->add("startedAt", DateTimeType::class, [
                "label" => "Date de prise de poste",
                "years" => range(date("Y"), date("Y") + 1),
                "constraints" => [
                    new GreaterThan([
                        "value" => "today",
                        "message" => "Date minimale : " . date("d/m/Y", strtotime(' +1 days')),
                    ])
                ]
            ])
            ->add("salary", TextType::class, [
                "label" => "Rémunération",
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description*",
                "attr" => [
                    "maxlength" => 2000,
                ]
            ])
            ->add("profilRequired", TextareaType::class, [
                "label" => "Profil requis*",
                "attr" => [
                    "maxlength" => 2000,
                ]
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
            ->add("type", ChoiceType::class, [
                "label" => "Type de contrat*",
                "placeholder" => "Choisissez une valeur",
                "choices" => [
                    "CDI" => "CDI",
                    "CDD" => "CDD",
                    "Stage" => "Stage",
                ],
            ])
            ->add("categories", EntityType::class, [
                "class" => Category::class,
                "label" => "Catégories*",
                "query_builder" => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder("c")
                        ->orderBy("c.name", "ASC");
                },
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => true,
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
