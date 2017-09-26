<?php

namespace TodoApp\Controller;

use DomainException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TodoApp\Model\TaskModel;

class TaskController
{
    public function getTasks(Application $app)
    {
        // Invoque le modèle chargé de manipuler et gérer les 'tasks' en base
        $taskModel = new TaskModel();
        $tasks = $taskModel->listAll();

        // Renvoie au client les données sous forme de JSON
        return $app->json( $tasks );
    }

    public function addTask(Application $app, Request $request)
    {
        /*
            L'objet  $request->request  contient la liste des paramètres
            envoyés avec la méthode POST.
            
            Cela équivaudrait à faire:
                $title = $_POST['title'];
        */
        $title = $request->request->get('title');

        $taskModel = new TaskModel();
        $insertedId = $taskModel->create( $title );

        // Renvoie un "code de réponse" en JSON : simplement pour que le client soit informé que sa requête a été traitée correctement par le serveur
        return $app->json( ['response' => 'ok', 'inserted' => $insertedId ] );
    }

    public function removeTask(Application $app, Request $request)
    {
        /*
            L'objet  $request->attributes  corresponds aux paramètres transmis
            dans l'URL de la requête.

            Si on est arrivé ici via la route:  /task/2  , alors $id vaudra 2
        */
        $id = $request->attributes->get('id');

        $taskModel = new TaskModel();
        $taskModel->remove($id);

        return $app->json( ['response' => 'ok' ]);
    }

    public function updateTask(Application $app, Request $request)
    {
        /*
            L'objet  $request->attributes  corresponds aux paramètres transmis
            dans l'URL de la requête.
            Si on est arrivé ici via la route:  /task/2  , alors $id vaudra 2
            
            L'objet  $request->request  contient la liste des paramètres
            envoyés avec la méthode POST.
            
            Cela équivaudrait à faire:
                $title  = $_POST['title'];
                $isDone = $_POST['isDone'];
        */
        $id = $request->attributes->get('id');

        $title  = $request->request->get('title');
        $isDone = $request->request->get('isDone');

        // Tente la modification d'une tâche en base avec les paramètres fournis par le client
        try
        {
            $taskModel = new TaskModel();
            $taskModel->update($id, $title, $isDone);
        }
        catch (DomainException $e)
        {
            // En cas d'échec (exception lancée par le modèle), on renvoie au client un code d'erreur sous la forme d'un JSON
            return $app->json( ['response' => 'error', 'message' => $e->getMessage()] );
        }

        return $app->json( ['response' => 'ok' ]);
    }
}