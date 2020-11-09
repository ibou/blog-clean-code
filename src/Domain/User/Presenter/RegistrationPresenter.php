<?php


namespace App\Domain\User\Presenter;

use App\Domain\User\Responder\ReadPostResponder;
use App\Domain\User\Responder\RegistrationResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class RegistrationPresenter
 * @package App\Domain\User\Presenter
 */
class RegistrationPresenter implements RegistrationPresenterInterface
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * ReadPostPresenter constructor.
     * @param Environment $twig
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate("security_login"));
    }


    /**
     * @param ReadPostResponder $responder
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function present(RegistrationResponder $responder): Response
    {
        return new Response(
            $this->twig->render(
                "registration/registration.html.twig",
                [
                    "form" => $responder->getForm(),
                ]
            )
        );
    }
}
