<?php

namespace TodoApp\Model;

use TodoApp\Infrastructure\Database;
use DomainException;

class TaskModel
{
    /**
     * Renvoie la liste de toutes les tâches de la base de données
     */
    public function listAll()
    {
        $database = new Database();

        $sql = 'SELECT id, title, isDone FROM tasks ORDER BY id ASC';

        $tasks = $database->query($sql);

        /*
            Petite opération permettant de transformer respectivement
            les valeur "1" et "0" en true et false, afin que de vrais
            Booléens soient renvoyés au client (Vue.js)
        */
        $tasks = array_map(function($task)
        {
            // Modification de la valeur de la clé 'isDone' en Booléen avec la fonction PHP : boolval()
            $task['isDone'] = boolval($task['isDone']);

            return $task;
        }, $tasks);

        // Renvoi du tableau de tâches
        return $tasks;
    }

    /**
     * Insère en base de donnée une nouvelle tâche avec l'intitulé $title
     */
    public function create($title)
    {
        // Vérification de la présence de l'intitulé
        if (empty($title))
        {
            throw new DomainException('Le parametre title est obligatoire.');
        }

        $database = new Database();

        $sql = 'INSERT INTO tasks (title, isDone) VALUES (?, 0)';

        return $database->executeSql($sql, [$title]);
    }

    /**
     * Supprime en base de donnée la tâche portant l'identifiant $id
     */
    public function remove($id)
    {
        $database = new Database();

        $sql = 'DELETE FROM tasks WHERE id = ?';

        return $database->executeSql($sql, [$id]);
    }

    /**
     * Modifie en base de donnée la tâche portant l'identifiant $id et
     * change son intitulé par $title et son état par $isDone
     */
    public function update($id, $title, $isDone)
    {
        // Vérification de la présence des paramètres nécessaires pour une modification
        if (empty($id) || empty($title) || empty($isDone))
        {
            throw new DomainException('Les parametres id, title, isDone sont obligatoires.');
        }

        /*
            Vérifie déjà si la tâche que l'on souhaite modifier existe en base
        */

        $database = new Database();

        $sql = 'SELECT id FROM tasks WHERE id = ?';

        $task = $database->queryOne($sql, [$id]);

        if (empty($task))
        {
            throw new DomainException("Cette tache n'existe pas.");
        }

        /*
            La tâche existe bien !
            Donc on l'update avec les paramètres reçus.
        */

        $sql = 'UPDATE tasks SET
            title = ?,
            isDone = ?
        WHERE id = ?';

        /*
            Reconversion du paramètre $isDone qui est une STRING "true" ou "false",
            et qu'il faut reconvertir en INT 1 ou 0 pour l'insérer dans
            le champs TINYINT de la base.
        */

        $isDone = intval(
            filter_var($isDone, FILTER_VALIDATE_BOOLEAN) // Converti la STRING "true" en BOOLÉEN true, et idem pour "false"
        ); // ... et intval() converti true en 1, et false en 0

        return $database->executeSql($sql, [$title, $isDone, $id]);
    }
}