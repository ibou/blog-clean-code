<?php


namespace App\EventSubscriber;


use App\Entity\Comment;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class CommentSubscriber implements EventSubscriberInterface
{

    private Security $security;

    /**
     * CommentTransfer constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
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
        if (!$event->getOriginalData() instanceof Comment){
            return;
        }
        $event->getData()->setAuthor($event->getOriginalData()->getAuthor());
        $event->getData()->setContent($event->getOriginalData()->getContent());

    }

    /**
     * @param ReverseEvent $event
     */
    public function onReverse(ReverseEvent $event): void
    {
        if (!$event->getOriginalData() instanceof Comment){
            return;
        }
        if ($this->security->isGranted('ROLE_USER')) {
            $event->getOriginalData()->setUser($this->security->getUser());
        }
        $event->getOriginalData()->setAuthor($event->getData()->getAuthor());
        $event->getOriginalData()->setContent($event->getData()->getContent());
    }
}