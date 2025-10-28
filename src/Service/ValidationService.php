<?php

namespace TicketFlow\Service;

use TicketFlow\Utils\Constants;

class ValidationService
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword(string $password): bool
    {
        return !empty($password) && strlen($password) >= 6;
    }

    public static function validateTicketTitle(string $title): bool
    {
        return !empty(trim($title));
    }

    public static function validateTicketStatus(string $status): bool
    {
        return in_array($status, Constants::TICKET_STATUSES);
    }

    public static function validateTicket(array $ticket): array
    {
        $errors = [];

        if (!self::validateTicketTitle($ticket['title'] ?? '')) {
            $errors['title'] = 'Title is required.';
        }

        if (!self::validateTicketStatus($ticket['status'] ?? '')) {
            $errors['status'] = 'Invalid status value.';
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors,
        ];
    }

    public static function validateAuthForm(string $email, string $password): array
    {
        $errors = [];

        if (!self::validateEmail($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (!self::validatePassword($password)) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors,
        ];
    }
}
