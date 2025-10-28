<?php

namespace TicketFlow\Utils;

class Constants
{
    const APP_NAME = "TicketFlow";

    const STORAGE_KEYS = [
        'SESSION' => 'ticketapp_session',
        'TICKETS' => 'ticketapp_tickets',
    ];

    const TICKET_STATUSES = [
        'OPEN' => 'open',
        'IN_PROGRESS' => 'in_progress',
        'CLOSED' => 'closed',
    ];

    const STATUS_COLORS = [
        'open' => 'bg-green-100 text-green-700',
        'in_progress' => 'bg-amber-100 text-amber-700',
        'closed' => 'bg-gray-200 text-gray-700',
    ];

    const DEMO_CREDENTIALS = [
        'EMAIL' => 'demo@ticket.com',
        'PASSWORD' => '123456',
    ];

    const MAX_CONTAINER_WIDTH = 'max-w-7xl';
}
