<?php
declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load configuration
$config = require __DIR__ . '/../config/config.php';

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => $config['twig_cache'],
    'debug' => $config['debug'],
]);

// Routes
$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_controller' => 'TicketFlow\\Controller\\LandingController::index']));
$routes->add('login', new Route('/auth/login', ['_controller' => 'TicketFlow\\Controller\\AuthController::login']));
$routes->add('register', new Route('/auth/register', ['_controller' => 'TicketFlow\\Controller\\AuthController::register']));
$routes->add('dashboard', new Route('/dashboard', ['_controller' => 'TicketFlow\\Controller\\DashboardController::index']));
$routes->add('tickets', new Route('/tickets', ['_controller' => 'TicketFlow\\Controller\\TicketsController::index']));

// Request handling
$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    $request->attributes->add($parameters);

    // ✅ Custom controller resolver that injects Twig
    $controllerResolver = new class($twig) extends ControllerResolver {
        private Environment $twig;

        public function __construct(Environment $twig)
        {
            parent::__construct();
            $this->twig = $twig;
        }

        protected function instantiateController(string $class): object
        {
            return match ($class) {
                'TicketFlow\\Controller\\LandingController' => new \TicketFlow\Controller\LandingController($this->twig),
                'TicketFlow\\Controller\\AuthController' => new \TicketFlow\Controller\AuthController($this->twig),
                'TicketFlow\\Controller\\DashboardController' => new \TicketFlow\Controller\DashboardController($this->twig),
                'TicketFlow\\Controller\\TicketsController' => new \TicketFlow\Controller\TicketsController($this->twig),
                default => new $class(),
            };
        }
    };

    $argumentResolver = new ArgumentResolver();

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (Exception $e) {
    $response = new Response('Not Found: ' . $e->getMessage(), 404);
}

$response->send();
