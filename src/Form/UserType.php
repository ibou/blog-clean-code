<?php


namespace App\Form;


use App\DataTransferObject\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label'=>'Adresse email : '
            ])
            ->add('pseudo', TextType::class, [
                'label'=>'Pseudo : '
            ])
            ->add('password', RepeatedType::class, [
                 'type'=>PasswordType::class,
                'first_options'=>[
                    'label'=>'Mot de passe : '
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe : '
                ],
                'invalid_message'=>'Les 2 mots de passes saisis ne sont pas identiques'
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', User::class);
    }


}