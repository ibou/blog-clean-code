<?php


namespace App\Form;


use App\Entity\Post;
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
 * @package App\Form
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
                    'help' => 'Veuillez saisir le titre du post !!!!',
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu : ',
                ]
            )
        ->add('file', FileType::class,[
            'mapped'=>false,
            'required'=>false,
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