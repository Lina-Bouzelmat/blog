<?php

namespace App\Form;

use App\Entity\article;
use App\Entity\Blog;
use App\Repository\ArticleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength' =>'50'
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\NotBlank()
            ]
        ])
        ->add('createur', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Createur',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
        ->add('theme', TextType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'Theme',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
        ])
        ->add('details', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '10',
            ],
            'label' => 'Details',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Favoris ?',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ]

            ])
            ->add('articles', EntityType::class, [
                'class' => Article::class,
                'query_builder' => function (ArticleRepository $r) {
                    return $r->createQueryBuilder('i')
                    ->orderBy('i.name', 'ASC');
                },
                'label' => 'Les articles',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('submit',SubmitType::class, [
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'sauvegarde'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
