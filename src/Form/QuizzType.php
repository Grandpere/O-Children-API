<?php

namespace App\Form;

use App\Entity\Quizz;
use App\Entity\World;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('image', FileType::class,[
                'label' => 'image (jpg,png,gif, svg)',
                'required' => false,
                'data_class' => null,
            ])
            ->add('world', EntityType::class, [
                'class' => World::class,
                'choice_label' => 'name',
                'expanded' => true,
            ])
            ->add('categories', null, [
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }
}
