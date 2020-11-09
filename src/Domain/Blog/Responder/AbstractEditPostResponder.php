<?php


namespace App\Domain\Blog\Responder;


use Symfony\Component\Form\FormView;

abstract class AbstractEditPostResponder
{

    /**
     * @var FormView
     */
    private FormView $form;

    /**
     * CreatePostResponder constructor.
     * @param FormView $form
     */
    public function __construct(FormView $form)
    {
        $this->form = $form;
    }

    /**
     * @return FormView
     */
    public function getForm(): FormView
    {
        return $this->form;
    }

}