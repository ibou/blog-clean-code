<?php


namespace App\Infrastructure\Handler;

use App\Infrastructure\Event\ReverseEvent;
use App\Infrastructure\Event\TransferEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractHandler
 * @package App\Infrastructure\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var FormInterface
     */
    protected FormInterface $form;
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    abstract protected function getDataTransferObject(): object;

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


    /**
     * @param Request $request
     * @param object $originalData
     * @param array $options
     * @return bool
     */
    public function handle(Request $request, object $originalData, array $options = []): bool
    {
        $data = $this->getDataTransferObject();
        $this->eventDispatcher->dispatch(new TransferEvent($originalData, $data), TransferEvent::NAME);
        $this->form = $this->formFactory->create($this->getFormType(), $data)->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->eventDispatcher->dispatch(new ReverseEvent($data, $originalData), ReverseEvent::NAME);
            $this->process($originalData);
            return true;
        }

        return false;
    }


    /**
     * @required
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @required
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
