<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
        'label' => 'Titre de la photo'
    ])
        ->add('description', TextareaType::class, [
            'label' => 'Description de la photo',
            'required' => false
        ])
        ->add('author', TextType::class, [
            'label' => 'Auteur de la photo'
        ])
        ->add('isPublished', CheckboxType::class, [
            'required' => false,
            'label' => 'Rendre la photo publique'
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'label' => 'Catégorie',
            'placeholder' => 'Choisir une catégorie'
        ])
        ->add('dateCreated',DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de création de la photo'
        ])
        ->add('dateUpdated',DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de modification de la photo',
            'required' => false,
        ])
        ->add('poster_file', FileType::class, [
            'label' => 'Photo de l\'article',
            'required' => false,
            'mapped' => false,
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
