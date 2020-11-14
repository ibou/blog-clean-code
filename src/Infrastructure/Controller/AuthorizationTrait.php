<?php


namespace App\Infrastructure\Controller;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Trait AuthorizationTrait
 * @package App\Infrastructure\Controller
 */
trait AuthorizationTrait
{

    /**
     * @var AuthorizationCheckerInterface
     */
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @required
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorization(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param string $attribute
     * @param object|null $subject
     */
    protected function denyAccessUnlessGranted(string $attribute, ?object $subject = null): void
    {
        if (!$this->authorizationChecker->isGranted($attribute, $subject)) {
            throw new AccessDeniedException();
        }
    }


    /**
     * @param string $attribute
     * @param object|null $subject
     * @return bool
     */
    protected function isGranted(string $attribute, ?object $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attribute, $subject);
    }

}