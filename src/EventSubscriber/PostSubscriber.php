<?php


namespace App\EventSubscriber;


use App\Entity\Post;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use App\Uploader\UploaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;

/**
 * Class PostSubscriber
 * @package App\EventSubscriber
 */
class PostSubscriber implements EventSubscriberInterface
{

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var UploaderInterface
     */
    private UploaderInterface $uploader;

    /**
     * PostSubscriber constructor.
     * @param Security $security
     * @param UploaderInterface $uploader
     */
    public function __construct(Security $security, UploaderInterface $uploader)
    {
        $this->security = $security;
        $this->uploader = $uploader;
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
        if (!$event->getOriginalData() instanceof Post){
            return;
        }
        $event->getData()->setTitle($event->getOriginalData()->getTitle());
        $event->getData()->setContent($event->getOriginalData()->getContent());
    }

    /**
     * @param ReverseEvent $event
     */
    public function onReverse(ReverseEvent $event): void
    {
        if (!$event->getOriginalData() instanceof Post){
            return;
        }
        /** @var UploadedFile $file */
        $file = $event->getData()->getImage();
        if (null !== $file) {
            $event->getOriginalData()->setImage($this->uploader->upload($file));
        }
        $event->getOriginalData()->setUser($this->security->getUser());
        $event->getOriginalData()->setTitle($event->getData()->getTitle());
        $event->getOriginalData()->setContent($event->getData()->getContent());
    }
}