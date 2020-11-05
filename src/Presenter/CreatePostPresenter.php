<?php


namespace App\Presenter;


class CreatePostPresenter extends AbstractEditPostPresenter implements CreatePostPresenterInterface
{
    protected function getView(): string
    {
        return "blog/create.html.twig";
    }

}