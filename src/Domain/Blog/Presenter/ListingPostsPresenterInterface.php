<?php


namespace App\Domain\Blog\Presenter;

use App\Domain\Blog\Responder\ListingPostsResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ListingPostsPresenterInterface
 * @package App\Domain\Blog\Presenter
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