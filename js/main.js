'use strict';

new Vue({

    // L'élément définissant le périmètre d'action de l'application Vue.js
    el   : '#app',

    // Modèle de données de l'application
    data : {
        tasks : [
            // { title : 'Apprendre HTML et CSS'         , isDone : true },
            // { title : 'Apprendre JavaScript et PHP'   , isDone : true },
            // { title : 'Apprendre Vue.js et Silex'     , isDone : false }
        ],
        newTaskTitle : ''
    },

    // Méthodes (fonctions) de l'application Vue.js
    methods : {
        addTask : function() {
            if (this.newTaskTitle.trim() === '') return;

            var newTask =
            {
                title : this.newTaskTitle,
                isDone: false
            };
            
            this.tasks.push( newTask );

            // Ajout en base
            jQuery.post('api/web/task', newTask);

            /*
                Réinitalise la valeur de la variable "newTaskName", utilisée
                en tant que modèle sur le <input type="text">
                Du coup, réinitialiser la variable "newTaskName" permet de
                vider la valeur de ce champs input
            */
            this.newTaskTitle = '';
        },
        removeTask : function(task) {
            var index = this.tasks.indexOf(task);

            if (index > -1) {
                this.tasks.splice(index, 1);

                // Suppression en base
                jQuery.ajax({
                    method : 'DELETE',
                    url    : 'api/web/task/' + task.id
                });
            }
        },
        onGetTasksFromServer : function(tasks) {
            this.tasks = tasks;
        },
        updateTask : function(task) {
            jQuery.post('api/web/task/' + task.id, task);
        }
    },

    /* Méthodes dites "computed" (pré-calculées) :
        Améliore les performances en n'évoquant les méthodes
        QUE si l'une des propriété en interne ne change */
    computed : {

        // Compte le nombre de tâches restantes à effectuer dans la liste de tâches
        remaining : function() {
            /*
                // Méthode "classique" :

                var compteur = 0;
                for (var index = 0; index < this.taches.length; index++)
                {
                    if (this.taches[index].estFaite === false)
                    {
                        compteur++;
                    }
                }
                return compteur;
            */

            // Méthode "fonctionnelle"
            return this.tasks.filter( function(task) {
                return task.isDone === false
            } ).length;
        }
    },

    // Filtres pour le formatage des données
    filters : {

        // Met au pluriel (si applicable) une expression
        pluralize : function(value, word) {
            return value + ' ' + word + (value > 1 ? 's' : '');
        }
    },

    /* Cette fonction sera évoquée lorsque l'instance de Vue aura été créé.
        C'est un peu là qu'on place le code d'initialisation */
    created : function() {
        jQuery.getJSON('api/web/tasks', this.onGetTasksFromServer.bind(this));
    }

});