<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\RegistrationHandler;
use App\Presenter\RegistrationPresenterInterface;
use App\Responder\RegistrationResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController
{
    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param RegistrationHandler $registrationHandler
     * @param RegistrationPresenterInterface $presenter
     * @return Response
     */
    public function __invoke(
        Request $request,
        RegistrationHandler $registrationHandler,
        RegistrationPresenterInterface $presenter
    ): Response {
        if ($registrationHandler->handle($request, new User())) {
            return $presenter->redirect();
        }

        return $presenter->present(new RegistrationResponder($registrationHandler->createView()));
    }
}
