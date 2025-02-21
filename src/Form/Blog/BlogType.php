<?php
namespace App\Form\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Title cannot be empty.']),
                    new Length(['min' => 3, 'max' => 255, 'minMessage' => 'Title must be at least {{ limit }} characters long.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [  
                    new NotBlank(['message' => 'Description cannot be empty.']),
                    new Length(['min' => 10, 'minMessage' => 'Description must be at least {{ limit }} characters long.']),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Upload Image (Optional)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPG, PNG, WEBP)',
                    ])
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'blog_item',
        ]);
    }
}
