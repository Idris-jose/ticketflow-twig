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
        ]);

        return new Response($content);
    }
}
