<?php


namespace App\Presenter;


use App\Responder\RegistrationResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface RegistrationPresenterInterface
 * @package App\Presenter
 */
interface RegistrationPresenterInterface
{

    public function redirect(): RedirectResponse;

    /**
     * @param RegistrationResponder $responder
     * @return Response
     */
    public function present(RegistrationResponder $responder): Response;
}