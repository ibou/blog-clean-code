<?php


namespace App\Presenter;

use App\Responder\AbstractEditPostResponder;
use App\Responder\AbstractRedirectPostResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface EditPostPresenterInterface
 * @package App\Presenter
 */
interface EditPostPresenterInterface
{

    /**
     * @param AbstractRedirectPostResponder $responder
     * @return RedirectResponse
     */
    public function redirect(AbstractRedirectPostResponder $responder): RedirectResponse;

    /**
     * @param AbstractEditPostResponder $responder
     * @return Response
     */
    public function present(AbstractEditPostResponder $responder): Response;
}