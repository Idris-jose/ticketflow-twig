<?php

namespace TicketFlow\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use TicketFlow\Service\AuthService;

class AuthController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $result = AuthService::login($email, $password);
            if ($result['success']) {
                return new RedirectResponse('/dashboard');
            }

            $content = $this->twig->render('pages/login.twig', [
                'error' => $result['message'],
                'email' => $email,
            ]);
            return new Response($content);
        }

        $content = $this->twig->render('pages/login.twig');
        return new Response($content);
    }

    public function register(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $result = AuthService::register($email, $password);
            if ($result['success']) {
                return new RedirectResponse('/auth/login');
            }

            $content = $this->twig->render('pages/register.twig', [
                'error' => $result['message'],
                'email' => $email,
            ]);
            return new Response($content);
        }

        $content = $this->twig->render('pages/register.twig');
        return new Response($content);
    }
}
