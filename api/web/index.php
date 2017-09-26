<?php

// Chargement du fichier d'autoload généré par Composer.
include __DIR__ . '/../vendor/autoload.php';

// Annonce à PHP que la classe 'Application' se trouve dans un espace de nom (namespace) 'Silex'
use Silex\Application;

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ExceptionHandler::register();

// Instancie cette classe 'Application' provenant de Silex
$app = new Application();

/* ****************************************************
 * CONFIGURATION DE L'APPLICATION
 * ****************************************************/

// Activation du mode debogage de Silex
$app['debug'] = true;

$app->get
(
    '/tasks',
    'TodoApp\Controller\TaskController::getTasks'
);

$app->post
(
    '/task',
    'TodoApp\Controller\TaskController::addTask'
);

$app->delete
(
    '/task/{id}',
    'TodoApp\Controller\TaskController::removeTask'
)
->assert('id', '\d+');

$app->post
(
    '/task/{id}',
    'TodoApp\Controller\TaskController::updateTask'
)
->assert('id', '\d+');

$app->get('/', function() use ($app)
{
    return $app->redirect('tasks');
});

$app->run();