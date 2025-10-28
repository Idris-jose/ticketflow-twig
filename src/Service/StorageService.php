<?php

namespace TicketFlow\Service;

class StorageService
{
    public static function getFromStorage(string $key): ?array
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }
        return $_SESSION[$key];
    }

    public static function setToStorage(string $key, array $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function removeFromStorage(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
