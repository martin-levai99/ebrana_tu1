<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "attr" => [
                    "class" => "form-control mb-3"
                ],
                "label" => "Název", 
                "required" => true
            ])
            ->add('description', CKEditorType::class, [
                "config" => [
                    "allowedContent" => 'p b i pre code; a[!href]',
                    "removeButtons" => 'Print,Preview,Templates,Source,Save,NewPage,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace '
                ],
                "label" => "Obsah"
            ])
            // ->add('posts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
