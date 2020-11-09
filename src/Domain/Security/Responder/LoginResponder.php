<?php


namespace App\Domain\Security\Responder;

use Symfony\Component\Form\FormView;

/**
 * Class LoginResponder
 * @package App\Domain\Security\Responder
 */
class LoginResponder
{
    private FormView $form;

    /**
     * LoginResponder constructor.
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
