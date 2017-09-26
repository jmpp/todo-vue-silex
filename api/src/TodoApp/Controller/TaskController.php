<?php

namespace TodoApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use TodoApp\Model\TaskModel;

class TaskController
{
    public function getTasks(Application $app)
    {
        $taskModel = new TaskModel();
        $tasks = $taskModel->listAll();

        return $app->json( $tasks );
    }

    public function addTask(Application $app, Request $request)
    {
        $title = $request->request->get('title');

        $taskModel = new TaskModel();
        $insertedId = $taskModel->create( $title );

        return $app->json( ['response' => 'ok', 'inserted' => $insertedId ] );
    }

    public function removeTask(Application $app, Request $request)
    {
        $id = $request->attributes->get('id');

        $taskModel = new TaskModel();
        $taskModel->remove($id);

        return $app->json( ['response' => 'ok' ]);
    }

    public function updateTask(Application $app, Request $request)
    {
        $id = $request->attributes->get('id');
        
        $title  = $request->request->get('title');
        $isDone = $request->request->get('isDone');

        try
        {
            $taskModel = new TaskModel();
            $taskModel->update($id, $title, $isDone);
        }
        catch (\DomainException $e)
        {
            return $app->json( ['response' => 'error', 'message' => $e->getMessage()] );
        }

        return $app->json( ['reponse' => 'ok' ]);
    }
}