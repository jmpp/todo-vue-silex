<?php

namespace TodoApp\Model;

use TodoApp\Infrastructure\Database;

class TaskModel
{
    public function listAll()
    {
        $database = new Database();

        $sql = 'SELECT
                   Id as id,
                   Title as title,
                   IsDone as isDone
                FROM Tasks';

        return $database->query($sql);
    }

    public function create($title)
    {
        $database = new Database();

        $sql = 'INSERT INTO Tasks (Title, IsDone) VALUES (?, 0)';

        return $database->executeSql($sql, [$title]);
    }

    public function remove($id)
    {
        $database = new Database();

        $sql = 'DELETE FROM Tasks WHERE Id = ?';

        return $database->executeSql($sql, [$id]);
    }

    public function update($id, $title, $isDone)
    {
        if (empty($id) || empty($title) || empty($isDone))
        {
            throw new \DomainException('Les parametres id, title, isDone sont obligatoires.');
        }

        $database = new Database();
        $task = $database->queryOne('SELECT Id FROM Tasks WHERE Id = ?', [$id]);

        if (empty($task))
        {
            throw new \DomainException("Cette teche n'existe pas.");
        }

        // var_dump($id, $title, $isDone);

        $sql = 'UPDATE Tasks SET
            Title = ?,
            IsDone = ?
        WHERE Id = ?';

        return $database->executeSql($sql, [$title, $isDone, $id]);
    }
}