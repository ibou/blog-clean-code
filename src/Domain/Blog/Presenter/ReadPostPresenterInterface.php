<?php


namespace App\Domain\Blog\Presenter;

use App\Domain\Blog\Responder\ReadPostResponder;
use App\Domain\Blog\Responder\RedirectReadPostResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ReadPostPresenterInterface
 * @package App\Domain\Blog\Presenter
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
