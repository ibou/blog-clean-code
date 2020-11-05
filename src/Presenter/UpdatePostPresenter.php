<?php


namespace App\Presenter;


/**
 * Class UpdatePostPresenterextends
 * @package App\Presenter
 */
class UpdatePostPresenter extends AbstractEditPostPresenter implements UpdatePostPresenterInterface
{
    protected function getView(): string
    {
        return "blog/update.html.twig";
    }

}