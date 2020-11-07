<?php


namespace App\Responder;


use App\Entity\Post;
use App\Representation\RepresentationInterface;
use Symfony\Component\Form\FormView;

/**
 * Class RegistrationResponder
 * @package App\Responder
 */
class RegistrationResponder
{

    /**
     * @var FormView
     */
    private FormView $form;

    /**
     * RegistrationResponder constructor.
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