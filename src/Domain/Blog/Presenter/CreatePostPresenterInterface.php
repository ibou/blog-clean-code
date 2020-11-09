<?php


namespace App\Domain\Blog\Presenter;

use App\Domain\Blog\Responder\CreatePostResponder;
use App\Domain\Blog\Responder\RedirectCreatePostResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface CreatePostPresenterInterface
 * @package App\Domain\Blog\Presenter
 */
interface CreatePostPresenterInterface extends EditPostPresenterInterface
{



}