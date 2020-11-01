<?php


namespace App\Handler;


use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractHandler implements HandlerInterface
{
    protected FormInterface $form;
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @required
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @param $data
     * @param array $options
     * @return bool
     */
    public function handle(Request $request, $data, array $options = []): bool
    {
        $this->form = $this->formFactory->create($this->getFormType(), $data)->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->process($data);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    abstract protected function getFormType(): string;

    /**
     * @param mixed $data
     */
    abstract protected function process($data): void;

    public function createView(): FormView
    {
        return $this->form->createView();
    }


}