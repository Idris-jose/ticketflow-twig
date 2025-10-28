<?php

namespace TicketFlow\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use TicketFlow\Service\AuthService;
use TicketFlow\Service\TicketService;

class DashboardController
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

        $user = AuthService::getCurrentUser();
        $stats = TicketService::getTicketStats();

        $content = $this->twig->render('pages/dashboard.twig', [
            'user' => $user,
            'stats' => $stats,
            'chartData' => json_encode([
                'labels' => ['Open', 'In Progress', 'Closed'],
                'datasets' => [[
                    'label' => 'Tickets',
                    'data' => [$stats['open'], $stats['in_progress'], $stats['closed']],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)', // green
                        'rgba(245, 158, 11, 0.8)', // amber
                        'rgba(107, 114, 128, 0.8)', // gray
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(245, 158, 11)',
                        'rgb(107, 114, 128)',
                    ],
                    'borderWidth' => 1
                ]]
            ]),
        ]);

        return new Response($content);
    }
}
