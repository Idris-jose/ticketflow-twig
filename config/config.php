<?php

return [
    'app_name' => 'TicketFlow',
    'debug' => true,
    'twig_cache' => false, // Set to a directory path in production
    'session_name' => 'ticketflow_session',
    'storage_keys' => [
        'session' => 'ticketapp_session',
        'tickets' => 'ticketapp_tickets',
    ],
    'demo_credentials' => [
        'email' => 'demo@ticket.com',
        'password' => '123456',
    ],
    'ticket_statuses' => [
        'open' => 'open',
        'in_progress' => 'in_progress',
        'closed' => 'closed',
    ],
];
