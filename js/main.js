'use strict';

new Vue({
    el   : '#app',
    data : {
        tasks : [
            // { title : 'Apprendre HTML et CSS'         , isDone : true },
            // { title : 'Apprendre JavaScript et PHP'   , isDone : true },
            // { title : 'Apprendre Vue.js et Silex'     , isDone : false }
        ],
        newTaskTitle : ''
    },
    methods : {
        addTask : function() {
            if (this.newTaskTitle.trim() === '') return;

            var newTask = {
                title : this.newTaskTitle,
                isDone: false
            };
            
            this.tasks.push( newTask );

            this.newTaskTitle = '';
        },
        removeTask : function(task) {
            var index = this.tasks.indexOf(task);

            if (index > -1) {
                this.tasks.splice(index, 1);
            }
        },
        onGetTasksFromServer : function(tasks) {
            this.tasks = tasks;
        }
    },
    computed : {
        remaining : function() {
            return this.tasks.filter( function(task) {
                return task.isDone === false
            } ).length;
        }
    },
    filters : {
        pluralize : function(value, word) {
            return value + ' ' + word + (value > 1 ? 's' : '');
        }
    },
    created : function() {
        jQuery.getJSON('api/web/tasks', this.onGetTasksFromServer.bind(this));
    }
})