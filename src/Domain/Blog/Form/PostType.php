<?php


namespace App\Domain\Blog\Form;

use App\Domain\Blog\DataTransferObject\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class PostType
 * @package App\Domain\Blog\Form
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre : ',
                    'attr' => ['class' => 'form-control'],
                    'row_attr' => ['class' => 'form-group'],
                    'label_attr' => ['class' => 'form-group-label'],
                    'help_attr' => ['class'=> 'form-control'],
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu : ',
                ]
            )
        ->add('image', FileType::class, [
            'required'=>false,
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class' => 'form-group'],
            'constraints'=>[
                new Image(),
                new NotNull([
                    'groups'=>'create'
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Post::class);
    }
}
