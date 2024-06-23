<?php

namespace App\Helper;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CreateUser
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle($entity): void
    {
        $user = $entity->getUser();
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

        if ($existingUser) {
            $entity->setUser($existingUser);
        } else {
            $this->entityManager->persist($user);
        }
    }
}