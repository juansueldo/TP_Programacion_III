<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);


$app->get('[/]', function (Request $request, Response $response) {    
  $payload = json_encode(array('method' => 'GET', 'msg' => "Inicio TP labo III"));
  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});
// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
});



$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{mesa}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
});

$app->group('/empleados', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
  $group->get('/{empleado}', \EmpleadoController::class . ':TraerUno');
  $group->post('[/]', \EmpleadoController::class . ':CargarUno');
});

$app->run();
