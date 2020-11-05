<?php


namespace App\Presenter;

use App\Responder\ListingPostsResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ListingPostsPresenterInterface
 * @package App\Presenter
 */
interface ListingPostsPresenterInterface
{
    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse;
    /**
     * @param ListingPostsResponder $responder
     * @return Response
     */
    public function present(ListingPostsResponder $responder): Response;
}