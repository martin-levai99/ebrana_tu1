<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                "attr" => [
                    "class" => "form-control mb-3"
                ]
            ])
            ->add('subject', TextType::class, [
                "attr" => [
                    "class" => "form-control mb-3"
                ],
                "label" => "Předmět"
            ])
            ->add('content', CKEditorType::class, [
                "config" => [
                    "allowedContent" => 'p b i pre code; a[!href]',
                    "removeButtons" => 'Print,Preview,Templates,Source,Save,NewPage,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace '
                ],
                "label" => "Obsah"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
