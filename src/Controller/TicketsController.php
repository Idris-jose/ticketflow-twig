<?php

namespace TicketFlow\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use TicketFlow\Service\AuthService;
use TicketFlow\Service\TicketService;
use TicketFlow\Utils\Constants;

class TicketsController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(Request $request): Response
    {
        if (!AuthService::isAuthenticated()) {
            return new RedirectResponse('/auth/login');
        }

        $tickets = TicketService::getAllTickets();
        $editing = null;
        $errors = [];
        $form = [
            'title' => '',
            'description' => '',
            'status' => Constants::TICKET_STATUSES['OPEN'],
        ];

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');

            if ($action === 'create' || $action === 'update') {
                $formData = [
                    'title' => $request->request->get('title'),
                    'description' => $request->request->get('description'),
                    'status' => $request->request->get('status'),
                ];

                if ($action === 'update') {
                    $ticketId = (int) $request->request->get('ticket_id');
                    $result = TicketService::updateTicket($ticketId, $formData);
                } else {
                    $result = TicketService::createTicket($formData);
                }

                if ($result['success']) {
                    return new RedirectResponse('/tickets');
                } else {
                    $errors = $result['errors'] ?? ['general' => $result['message'] ?? 'Unknown error'];
                    $form = $formData;
                    if ($action === 'update') {
                        $editing = $ticketId;
                    }
                }
            } elseif ($action === 'delete') {
                $ticketId = (int) $request->request->get('ticket_id');
                TicketService::deleteTicket($ticketId);
                return new RedirectResponse('/tickets');
            } elseif ($action === 'edit') {
                $ticketId = (int) $request->request->get('ticket_id');
                $ticket = array_filter($tickets, fn($t) => $t['id'] === $ticketId);
                if (!empty($ticket)) {
                    $ticket = reset($ticket);
                    $form = [
                        'title' => $ticket['title'],
                        'description' => $ticket['description'],
                        'status' => $ticket['status'],
                    ];
                    $editing = $ticketId;
                }
            }
        }

        $content = $this->twig->render('pages/tickets.twig', [
            'tickets' => $tickets,
            'editing' => $editing,
            'form' => $form,
            'errors' => $errors,
            'ticket_statuses' => Constants::TICKET_STATUSES,
            'status_colors' => Constants::STATUS_COLORS,
            'max_container_width' => Constants::MAX_CONTAINER_WIDTH,
        ]);

        return new Response($content);
    }
}
