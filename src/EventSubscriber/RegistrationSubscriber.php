<?php


namespace App\EventSubscriber;


use App\Entity\Comment;
use App\Entity\User;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class RegistrationSubscriber
 * @package App\EventSubscriber
 */
class RegistrationSubscriber implements EventSubscriberInterface
{


    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * RegistrationSubscriber constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    public static function getSubscribedEvents()
    {
        return [
            TransferEvent::NAME => "onTransfer",
            ReverseEvent::NAME => "onReverse",
        ];
    }

    /**
     * @param TransferEvent $event
     */
    public function onTransfer(TransferEvent $event): void
    {
    }

    /**
     * @param ReverseEvent $event
     */
    public function onReverse(ReverseEvent $event): void
    {
        if (!$event->getOriginalData() instanceof User) {
            return;
        }
        $event->getOriginalData()->setPseudo($event->getData()->getPseudo());
        $event->getOriginalData()->setPassword(
            $this->userPasswordEncoder->encodePassword($event->getOriginalData(), $event->getData()->getPassword())
        );
        $event->getOriginalData()->setEmail($event->getData()->getEmail());
    }
}