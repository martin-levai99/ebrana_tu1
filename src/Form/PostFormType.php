<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
