<?php


namespace App\Domain\Blog\Handler;


use App\Domain\Blog\DataTransferObject\Post;
use App\Domain\Blog\Form\PostType;
use App\Infrastructure\Handler\AbstractHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

/**
 * Class PostHandler
 * @package App\Domain\Blog\Handler
 */
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
     * @return string
     */
    protected function getFormType(): string
    {
        return PostType::class;
    }

    /**
     * @param mixed $data
     */
    protected function process($data): void
    {
        if ($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW) {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }

    /**
     * @return object
     */
    protected function getDataTransferObject(): object
    {
        return new Post();
    }
}