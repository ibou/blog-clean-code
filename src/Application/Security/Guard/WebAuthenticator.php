<?php


namespace App\Application\Security\Guard;


use App\Domain\Security\DataTransferObject\Credentials;
use App\Domain\Security\Form\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class WebAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';

    private UrlGeneratorInterface $urlGenerator;

    private FormFactoryInterface $formFactory;

    private UserPasswordEncoderInterface $passwordEncoder;


    /**
     * WebAuthenticator constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param FormFactoryInterface $formFactory
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
    }



    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);

    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->formFactory->create(LoginType::class, $credentials)->handleRequest($request);
        if (!$form->isValid()) {
            return null;
        }

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials->getUsername());
        if (!$user) {
            throw new UsernameNotFoundException("Email could not be found !!!! ");
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($valid = $this->passwordEncoder->isPasswordValid($user, $credentials->getPassword())) {
            return true;
        }

        throw new AuthenticationException('Password not valid.');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('index'));
    }


}