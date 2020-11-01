<?php


namespace App\Handler;


use App\DataTransferObject\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class PostHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * PostHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        if ($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW) {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }

    protected function getDataTransferObject(): object
    {
        return new Post();
    }
}