<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Vich\UploaderBundle\Form\Type\VichFileType;


class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add("avatarFile", VichFileType::class, [
            "allow_delete" => false,
            "by_reference" => false,
            "download_link" => false,
            "required" => false,
            "label" => false,
            "constraints" => [
                new NotNull(),
                new File([
                    "maxSize" => "1M",
                     "mimeTypes" => [
                        "image/jpeg",
                        "image/png",
                    ],
                ])],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Avatar::class,
            "csrf_protection" => true,
        ]);
    }
}
