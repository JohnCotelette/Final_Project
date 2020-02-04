<?php

namespace App\Form;

use App\Entity\Cv;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("cvFile", VichFileType::class, [
                "label" => "Cv (au format PDF)",
                "attr" => [
                    "class" => "form-group",
                ],
                "required" => true,
                "allow_delete" => true,
                "download_label" => true,
                "by_reference" => false,
                "constraints" => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Le Cv doit être au format PDF',
                        'maxSizeMessage' => 'Votre fichier est trop volumineux ({{ limit }} maximum)',
                    ])],
            ])
            ->add("save", SubmitType::class, [
                "label" => "télécharger ",
                "attr" => [
                    "class" => "btn btn-primary",
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
            'csrf_protection' => true,
        ]);
    }
}
