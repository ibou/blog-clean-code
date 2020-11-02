<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Handler\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class RegistrationController
{
    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param Environment $twig
     * @param RegistrationHandler $registrationHandler
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(
        Request $request,
        UserPasswordEncoderInterface $userPasswordEncoder,
        Environment $twig,
        RegistrationHandler $registrationHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        if ($registrationHandler->handle($request, new User())) {
            return new RedirectResponse($urlGenerator->generate("security_login"));
        }

        return new Response(
            $twig->render(
                'registration/registration.html.twig',
                [
                    'form' => $registrationHandler->createView(),
                ]
            )
        );
    }
}
