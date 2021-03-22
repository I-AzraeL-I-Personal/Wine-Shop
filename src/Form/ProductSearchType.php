<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'query_builder' => function(CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('category')
                        ->andWhere('category.id BETWEEN 1 AND 3');
                },
            ])
            ->add('taste', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'query_builder' => function(CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('category')
                        ->andWhere('category.id BETWEEN 4 AND 7');
                },
            ])
            ->add('max', ChoiceType::class, [
                'choices' => range(20, 200, 20),
                'choice_label' => function($choice) {
                    return $choice;
                }
            ])
        ;
    }
}
