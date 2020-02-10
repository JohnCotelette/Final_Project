<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;


class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add("avataruser", VichFileType::class, [
            "label" => " choisir un avatar",

            "attr" => [
                "class" => " ",
            ],
            "required" => true,
            "allow_delete" => true,
            "download_label" => true,
            "by_reference" => false,
            "constraints" => [
                new File([
                    "maxSize" => "1M",
                     "mimeTypes" => [
                        "image/jpeg",
                        "image/png",
                    ],
                    'mimeTypesMessage' => "l'avatar doit étre  au format jpeg ou png",
                    'maxSizeMessage' => 'Votre fichier est trop volumineux ({{ limit }} maximum)',
                ])],
        ])
        ->add("save", SubmitType::class, [
            "label" => "ajouter/modifier avatar",
            "attr" => [
                "class" => "btn btn-primary",
            ]
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
        ]);
    }
}
