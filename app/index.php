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
require_once './middlewares/MWAccesos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/LoginController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/ArchivosController.php';

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


$app->group('/login', function (RouteCollectorProxy $group) {

  $group->post('[/]', \LoginController::class . ':verificarUsuario');
});

//* USUARIOS
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos'); 
  $group->get('/{id}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno'); 
  $group->put('/', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  $group->post('/login/', \UsuarioController::class . ':Login'); 
})->add(\MWAccesos::class . ':esSocio');

//* EMPLEADOS 
$app->group('/empleados', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EmpleadoController::class . ':TraerTodos'); 
  $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
  $group->post('[/]', \EmpleadoController::class . ':CargarUno'); 
  $group->put('/', \EmpleadoController::class . ':ModificarUno');
  $group->delete('/{id}', \EmpleadoController::class . ':BorrarUno');
})->add(\MWAccesos::class . ':esSocio');

//* PRODUCTOS 
$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos') 
    ->add(\MWAccesos::class . ':esEmpleado'); 
  $group->post('[/]', \ProductoController::class . ':CargarUno') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->put('/modificar', \ProductoController::class . ':ModificarUno') 
    ->add(\MWAccesos::class . ':esEmpleado');
    $group->put('/modificardos', \ProductoController::class . ':ModificarUnoDos') 
    ->add(\MWAccesos::class . ':esEmpleado');
});

//* PEDIDOS 
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->post('[/]', \PedidoController::class . ':CargarUno') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->put('/modificar', \PedidoController::class . ':ModificarUno') 
    ->add(\MWAccesos::class . ':esEmpleado');
  $group->get('/lista', \PedidoController::class . ':TraerPedidosTiempo') 
    ->add(\MWAccesos::class . ':esSocio');
  
});

//* MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->get('/lista', \MesaController::class . ':TraerTodos')
      ->add(\MWAccesos::class . ':esSocio');
  $group->put('/cobrar', \MesaController::class . ':CobrarUno') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->put('/modificar', \MesaController::class . ':ModificarUno') 
    ->add(\MWAccesos::class . ':esMozo');
  $group->put('/cerrarmesa', \MesaController::class . ':CerrarMesa') 
  ->add(\MWAccesos::class . ':esSocio');
  $group->post('/crearmesa', \MesaController::class . ':CargarUno') 
  ->add(\MWAccesos::class . ':esSocio');
});

$app->group('/cliente', function (RouteCollectorProxy $group) {
  $group->get('/mesa/{mesa_id}/{nro_pedido}', \MesaController::class . ':TraerDemoraPedidoMesa'); 
  $group->post('/encuesta', \EncuestaController::class . ':RealizarEncuesta'); 
});

$app->group('/socio', function (RouteCollectorProxy $group) {
  $group->get('/obtenerencuestas/{limite}', \EncuestaController::class . ':ObtenerMejores');
  $group->get('/obtenermesa', \MesaController::class . ':MesaMasUsada');
})->add(\MWAccesos::class . ':esSocio');

$app->group('/archivos', function (RouteCollectorProxy $group) {
  $group->get('/guardarcsv', \ArchivosController::class . ':Guardar'); 
  $group->get('/leercsv', \ArchivosController::class . ':Leer');
  $group->post('/guardarpdf', \ArchivosController::class . ':DownloadPdf');
  $group->get('/productostarde', \ProductoController::class . ':TraerTodosTarde');
  $group->get('/pedidostarde', \PedidoController::class . ':TraerTodosTarde');   
})->add(\MWAccesos::class . ':esSocio');

$app->run();
