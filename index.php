<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/libs/router/router.php';
require_once __DIR__ . '/controllers/PerfumesApiController.php';

try {
    
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $stmt = $pdo->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    if ($stmt->rowCount() === 0) {
        require_once __DIR__ . '/app/db/init.php';
    } else {
        
        $pdo->exec('USE `' . DB_NAME . '`');
    }
} catch (PDOException $e) {
   
    die('Error al conectar o crear la base de datos: ' . $e->getMessage());
}




$router = new Router();

// Rutas de la API para Perfumes
$router->addRoute('perfumes', 'GET', 'PerfumesApiController', 'getAll');
$router->addRoute('perfumes/:id', 'GET', 'PerfumesApiController', 'get');
$router->addRoute('perfumes', 'POST', 'PerfumesApiController', 'create');
$router->addRoute('perfumes/:id', 'PUT', 'PerfumesApiController', 'update');
$router->addRoute('perfumes/:id', 'DELETE', 'PerfumesApiController', 'delete');

$router->setDefaultRoute('PerfumesApiController', 'notFound');


$router->route($_GET['resource'] ?? '', $_SERVER['REQUEST_METHOD']);