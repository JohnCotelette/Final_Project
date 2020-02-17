<?php

namespace App\Form;

use App\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ApplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("motivation", TextareaType::class, [
                "attr" => [
                    "placeholder" => "Message de candidature",
                    "maxlength" => 1000,
                ],
                "label" => false,
                "constraints" => [
                    new Length([
                        "max" => 1000,
                        "maxMessage" => "Votre message est trop long (max : {{ limit }} caractères)",
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Application::class,
            "csrf_protection" => true,
            "attr" => [
                "novalidate" => "novalidate",
            ],
        ]);
    }
}
