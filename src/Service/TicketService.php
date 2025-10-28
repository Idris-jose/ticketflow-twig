<?php

namespace TicketFlow\Service;

use TicketFlow\Utils\Constants;

class TicketService
{
    public static function getAllTickets(): array
    {
        return StorageService::getFromStorage(Constants::STORAGE_KEYS['TICKETS']) ?? [];
    }

    public static function createTicket(array $data): array
    {
        $validation = ValidationService::validateTicket($data);
        if (!$validation['isValid']) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        $tickets = self::getAllTickets();
        $newTicket = [
            'id' => time(),
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'status' => $data['status'],
            'createdAt' => date('c'),
        ];

        $tickets[] = $newTicket;
        StorageService::setToStorage(Constants::STORAGE_KEYS['TICKETS'], $tickets);

        return ['success' => true, 'ticket' => $newTicket];
    }

    public static function updateTicket(int $id, array $data): array
    {
        $validation = ValidationService::validateTicket($data);
        if (!$validation['isValid']) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        $tickets = self::getAllTickets();
        foreach ($tickets as &$ticket) {
            if ($ticket['id'] === $id) {
                $ticket = array_merge($ticket, $data);
                StorageService::setToStorage(Constants::STORAGE_KEYS['TICKETS'], $tickets);
                return ['success' => true, 'ticket' => $ticket];
            }
        }

        return ['success' => false, 'message' => 'Ticket not found'];
    }

    public static function deleteTicket(int $id): bool
    {
        $tickets = self::getAllTickets();
        $filtered = array_filter($tickets, fn($ticket) => $ticket['id'] !== $id);

        if (count($filtered) === count($tickets)) {
            return false; // Ticket not found
        }

        StorageService::setToStorage(Constants::STORAGE_KEYS['TICKETS'], array_values($filtered));
        return true;
    }

    public static function getTicketStats(): array
    {
        $tickets = self::getAllTickets();
        $stats = [
            'total' => count($tickets),
            'open' => 0,
            'in_progress' => 0,
            'closed' => 0,
        ];

        foreach ($tickets as $ticket) {
            $status = $ticket['status'];
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return $stats;
    }
}
