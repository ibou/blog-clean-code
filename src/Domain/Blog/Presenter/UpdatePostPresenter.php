<?php


namespace App\Domain\Blog\Presenter;


/**
 * Class UpdatePostPresenterextends
 * @package App\Domain\Blog\Presenter
 */
class UpdatePostPresenter extends AbstractEditPostPresenter implements UpdatePostPresenterInterface
{
    protected function getView(): string
    {
        return "blog/update.html.twig";
    }

}