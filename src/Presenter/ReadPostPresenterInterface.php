<?php


namespace App\Presenter;


use App\Responder\ReadPostResponder;
use App\Responder\RedirectReadPostResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ReadPostPresenterInterface
 * @package App\Presenter
 */
interface ReadPostPresenterInterface
{

    public function redirect(RedirectReadPostResponder $response): Response;

    /**
     * @param ReadPostResponder $responder
     * @return Response
     */
    public function present(ReadPostResponder $responder): Response;
}