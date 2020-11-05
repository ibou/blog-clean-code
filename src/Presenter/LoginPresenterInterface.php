<?php


namespace App\Presenter;


use App\Responder\LoginResponder;
use Symfony\Component\HttpFoundation\Response;

interface LoginPresenterInterface
{

    /**
     * @param LoginResponder $responder
     * @return Response
     */
    public function present(LoginResponder $responder): Response;
}