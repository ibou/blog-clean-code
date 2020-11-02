<?php

namespace App\Controller;

use App\DataTransferObject\Credentials;
use App\Form\LoginType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController

{
    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        Environment $twig
    ): Response {
        $form = $formFactory->create(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));

        if (null !== $authenticationUtils->getLastAuthenticationError(false)) {
            $form->addError(
                new FormError($authenticationUtils->getLastAuthenticationError()->getMessage())
            );
        }

        return new Response(
            $twig->render(
                'security/login.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
