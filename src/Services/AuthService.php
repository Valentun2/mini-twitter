<?php

namespace App\Services;


use App\Models\User;
use App\Services\PasswordService;
use DomainException;
use RuntimeException;

class AuthService
{
    public function __construct(
        private User $users,
        private PasswordService $passwords
    ) {}

    public function register(string $name, string $email, string $password): int|false
    {
        if ($this->users->findByEmail($email)) {
            throw new DomainException('Цей Email вже зареєстрований');
        }

        $hash = $this->passwords->hash($password);

        $userId = $this->users->create($name, $email, $hash);

        if (!$userId) {
            throw new RuntimeException('Не вдалося створити користувача');
        }

        return $userId;
    }

    public function login(string $email, string $password): array|false
    {
        $user = $this->users->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!$this->passwords->verify($password, $user['password'])) {
            return false;
        }

        unset($user['password']);

        return $user;
    }
}
