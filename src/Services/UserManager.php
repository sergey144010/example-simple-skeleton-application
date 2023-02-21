<?php

namespace App\Services;

use App\Collection;
use App\EntityManager;
use App\Models\User;
use App\PersistOptions;
use App\Repositories\UserRepository;

class UserManager
{
    public function __construct(private EntityManager $entityManager, private UserRepository $userRepository)
    {
    }

    public function createUser(string $name, string $lastName, int $age = null, string $from = null): void
    {
        $user = $this->userDefault($name, $lastName);
        if (isset($age)) {
            $user->setAge($age);
        }
        if (isset($from)) {
            $user->setFrom($from);
        }

        $this->entityManager->persist($user, new PersistOptions('user'));
        $this->entityManager->flush();
    }

    private function userDefault(string $name, string $lastName): User
    {
        $user = new User($name, $lastName);
        /** same key create logic */
        $user->setKey(hash('sha256', $name));

        return $user;
    }

    public function createUsers(array $responseUsersList): void
    {
        foreach ($responseUsersList as $item) {
            $this->entityManager->persist(
                $this->userDefault($item['name'], $item['lastName']),
                new PersistOptions('user')
            );
        }

        $this->entityManager->flush();
    }

    public function usersByName(string $name): Collection
    {
        return$this->userRepository->findByName($name);
    }

    public function usersByNames(array $nameList): Collection
    {
        return$this->userRepository->findByNames($nameList);
    }

    public function usersOlder(int $age): Collection
    {
        return$this->userRepository->findOlderAge($age);
    }
}
