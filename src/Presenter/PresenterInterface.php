<?php


namespace App\Presenter;

use App\Responder\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface PresenterInterface
 * @package App\Presenter
 */
interface PresenterInterface
{
    /**
     * @param ResponderInterface $responder
     * @return Response
     */
    public function present(ResponderInterface $responder): Response;
}