<?php


namespace App\Domain\Security\Presenter;

use App\Domain\Security\Responder\LoginResponder;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginPresenter implements LoginPresenterInterface
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * LoginPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function present(LoginResponder $responder): Response
    {
        return new Response(
            $this->twig->render(
               'security/login.html.twig',
               [
                   'form' => $responder->getForm()
               ]
           )
        );
    }
}
