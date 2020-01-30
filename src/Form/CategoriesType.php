<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("category", EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name",
                "label" => false,
                "expanded" => true,
                'group_by' => function($choice, $key, $value) {
                    return $choice->getField()->getName();
                },
            ])
            ->add("experience", ChoiceType::class, [
                "expanded" => true,
                "choices" => [
                    "Tous" => null,
                    "Junior (0 à 2 ans)" => "Junior (0 à 2 ans)",
                    "Confirmé (3 à 6 ans)" => "Confirmé (3 à 6 ans)",
                    "Senior (7 ans et plus)" => "Senior (7 ans et plus)",
                ],
            ])
            ->add("salary", ChoiceType::class, [
                "expanded" => true,
                "multiple" => false,
                "choices" => [
                    "Tous" => null,
                    "25K" => 25000,
                    "30K" => 30000,
                    "35K" => 35000,
                    "40K" => 40000,
                ],
            ])
            ->add("type", ChoiceType::class, [
                "expanded" => true,
                "multiple" => false,
                "choices" => [
                    "Tous" => null,
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
            "csrf_protection" => true,
            "method" => "GET",
            "attr" => [
                "novalidate" => "novalidate",
            ],
        ]);
    }
}
