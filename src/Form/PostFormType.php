<?php

namespace App\Form;

use App\Entity\Post;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "attr" => [
                    "class" => "form-control mb-3"
                ]
            ])
            ->add('excerpt', CKEditorType::class)
            ->add('content', CKEditorType::class)
            ->add('publishDate', DateType::class, [
                "attr" => [
                    "class" => " mb-3"
                ],
                'format' => 'd. M. y'
            ])
            ->add('thumbnail', FileType::class, [
                "required" => false,
                "mapped" => false,
                
                "attr" => [
                    "class" => "form-control mb-3"
                ]
            ])
            // ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
