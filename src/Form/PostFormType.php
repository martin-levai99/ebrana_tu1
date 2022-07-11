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
                ],
                "label" => "Název"
            ])
            ->add('excerpt', CKEditorType::class, [
                "config" => [
                    "allowedContent" => 'p b i pre code; a[!href]',
                    "removeButtons" => 'Print,Preview,Templates,Source,Save,NewPage,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace '
                ],
                "label" => "Popisek"
            ])
            ->add('content', CKEditorType::class, [
                "config" => [
                    "allowedContent" => 'p b i pre code img table h1 h2 h3 h4 h5 h6; a[!href]',
                    "removeButtons" => 'Print,Preview,Templates,Source,Save,NewPage,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace '
                ],
                "label" => "Obsah",
            ])
            ->add('publishDate', DateType::class, [
                "attr" => [
                    "class" => " mb-3"
                ],
                'format' => 'd. M. y',
                "label" => "Datum publikace",
            ])
            ->add('thumbnail', FileType::class, [
                "required" => false,
                "mapped" => false,
                "attr" => [
                    "class" => "form-control mb-3"
                ],
                "label" => "Náhledový obrázek",
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                "label" => "Kategorie",
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
