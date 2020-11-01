<?php


namespace App\Handler;


use App\Form\PostType;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UploaderInterface
     */
    private UploaderInterface $uploader;

    /**
     * PostHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param UploaderInterface $uploader
     */
    public function __construct(EntityManagerInterface $entityManager, UploaderInterface $uploader)
    {
        $this->entityManager = $entityManager;
        $this->uploader = $uploader;
    }


    /**
     * @inheritDoc
     */
    protected function getFormType(): string
    {
        return PostType::class;
    }

    /**
     * @inheritDoc
     */
    protected function process($data): void
    {
        /** @var UploadedFile $file */
        $file = $this->form->get('file')->getData();
        if (null !== $file) {
            $data->setImage($this->uploader->upload($file));
        }
        if ($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW) {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }
}