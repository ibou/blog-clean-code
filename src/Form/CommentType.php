<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType
 * @package App\Form
 */
class CommentType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "content",
                TextareaType::class,
                [
                    "label" => "Votre message :",
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $events) {
                if ($events->getData()->getUser() !== null) {
                    return;
                }

                $events->getForm()->add(
                    "author",
                    TextType::class,
                    [
                        "label" => "Pseudo :",
                        'attr' => ['class' => 'form-control'],
                        'row_attr' => ['class' => 'form-group'],
                        'label_attr' => ['class' => 'form-group-label'],
                    ]
                );

            }
        );
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", Comment::class);
    }
}