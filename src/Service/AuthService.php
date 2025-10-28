<?php

namespace TicketFlow\Service;

use TicketFlow\Utils\Constants;

class AuthService
{
    public static function login(string $email, string $password): array
    {
        $validation = ValidationService::validateAuthForm($email, $password);
        if (!$validation['isValid']) {
            return ['success' => false, 'message' => implode(' ', $validation['errors'])];
        }

        if ($email === Constants::DEMO_CREDENTIALS['EMAIL'] && $password === Constants::DEMO_CREDENTIALS['PASSWORD']) {
            $mockUser = ['email' => $email, 'token' => 'fake_token_' . time()];
            StorageService::setToStorage(Constants::STORAGE_KEYS['SESSION'], $mockUser);
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    public static function register(string $email, string $password): array
    {
        $validation = ValidationService::validateAuthForm($email, $password);
        if (!$validation['isValid']) {
            return ['success' => false, 'message' => implode(' ', $validation['errors'])];
        }

        $mockUser = ['email' => $email, 'token' => 'fake_token_' . time()];
        StorageService::setToStorage(Constants::STORAGE_KEYS['SESSION'], $mockUser);
        return ['success' => true];
    }

    public static function logout(): void
    {
        StorageService::removeFromStorage(Constants::STORAGE_KEYS['SESSION']);
    }

    public static function getCurrentUser(): ?array
    {
        return StorageService::getFromStorage(Constants::STORAGE_KEYS['SESSION']);
    }

    public static function isAuthenticated(): bool
    {
        return self::getCurrentUser() !== null;
    }
}
