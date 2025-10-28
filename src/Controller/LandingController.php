<?php

namespace TicketFlow\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use TicketFlow\Utils\Constants;

class LandingController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(Request $request): Response
    {
        $features = [
            [
                'title' => 'Fast Ticketing',
                'text' => 'Create and assign tickets instantly to keep your workflow moving.',
            ],
            [
                'title' => 'Track Progress',
                'text' => 'Visualize ticket status from open to resolved in one view.',
            ],
            [
                'title' => 'Secure Access',
                'text' => 'Only authorized users can manage tickets via secure session tokens.',
            ],
        ];

        $content = $this->twig->render('pages/landing.twig', [
            'features' => $features,
            'max_container_width' => Constants::MAX_CONTAINER_WIDTH,
        ]);

        return new Response($content);
    }
}
