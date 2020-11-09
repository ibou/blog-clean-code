<?php


namespace App\Application\Security\Voter;


use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class PostVoter
 * @package App\Application\Security\Voter
 */
class PostVoter extends \Symfony\Component\Security\Core\Authorization\Voter\Voter
{
    public const EDIT = 'edit';


    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject)
    {
        if (!($subject instanceof Post)) {
            return false;
        }

        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        switch ($attribute) {
            case self::EDIT:
                return $user === $subject->getUser();
            default :
                return false;
        }
    }
}