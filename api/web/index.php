<?php

// Chargement du fichier d'autoload généré par Composer.
include __DIR__ . '/../vendor/autoload.php';

// Annonce à PHP que la classe 'Application' se trouve dans un espace de nom (namespace) 'Silex'
use Silex\Application;

// Instancie cette classe 'Application' provenant de Silex
$app = new Application();

// Activation du mode debogage de Silex
$app['debug'] = true;

/* ****************************************************
 * CONFIGURATION DES ROUTES DE L'APPLICATION SILEX
 * ****************************************************/

/**
 * Renvoie un JSON de la liste complète des tâches en base de données
 * 
 * GET /tasks
 * Route gérée par TaskController::getTasks
 */
$app->get
(
    '/tasks',
    'TodoApp\Controller\TaskController::getTasks'
);

/**
 * Ajoute une nouvelle tâche dans la base de données
 * 
 * POST /task
 * Route gérée par TaskController::addTask
 */
$app->post
(
    '/task',
    'TodoApp\Controller\TaskController::addTask'
);

/**
 * Supprime la tâche {id} de la base de données
 * 
 * DELETE /task/{id}
 * Route gérée par TaskController::addTask
 */
$app->delete
(
    '/task/{id}',
    'TodoApp\Controller\TaskController::removeTask'
)
->assert('id', '\d+'); // Indique que le paramètre {id} doit être numérique dans l'URL

/**
 * Modifie l'intitulé et l'état de la tâche {id} dans la base de données
 * 
 * POST /task/{id}
 * Route gérée par TaskController::addTask
 */
$app->post
(
    '/task/{id}',
    'TodoApp\Controller\TaskController::updateTask'
)
->assert('id', '\d+'); // Indique que le paramètre {id} doit être numérique dans l'URL

/**
 * Simple redirection vers la route: /tasks
 * 
 * GET /
 */
$app->get('/', function() use ($app)
{
    return $app->redirect('tasks');
});

// Démarrage de l'application Silex
$app->run();