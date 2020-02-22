<?php

namespace App\Form;

use App\Entity\Business;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("description", TextareaType::class, [
                "label" => "Description",
                "attr" => [
                    "class" => "textCounted",
                    "maxlength" => 5000,
                ],
            ])
            ->add("whyUs", TextareaType::class, [
                "label" => "Pourquoi nous ?",
                "attr" => [
                    "class" => "textCounted",
                    "maxlength" => 5000,
                ],
            ])
            ->add("kind", TextType::class, [
                "label" => "Type d'entreprise",
                "attr" => [
                    "maxlength" => 100,
                ],
            ])
            ->add("activityArea", TextType::class, [
                "label" => "Secteur d'activité",
                "attr" => [
                    "maxlength" => 255,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Business::class,
            "csrf_protection" => true,
            "attr" => [
                "id" => "formBusiness",
                "class" => "invisible",
            ],
        ]);
    }
}
