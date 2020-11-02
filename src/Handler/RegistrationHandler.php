<?php


namespace App\Handler;


use App\DataTransferObject\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * RegistrationHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function getDataTransferObject(): object
    {
         return new User();
    }

    /**
     * @inheritDoc
     */
    protected function getFormType(): string
    {
        return UserType::class;
    }

    /**
     * @inheritDoc
     */
    protected function process($data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}