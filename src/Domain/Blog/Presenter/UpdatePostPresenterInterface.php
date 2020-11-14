<?php


namespace App\Domain\Blog\Presenter;

use App\Domain\Blog\Responder\RedirectUpdatePostResponder;
use App\Domain\Blog\Responder\UpdatePostResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface UpdatePostPresenterInterface
 * @package App\Domain\Blog\Presenter
 */
interface UpdatePostPresenterInterface extends EditPostPresenterInterface
{
}
