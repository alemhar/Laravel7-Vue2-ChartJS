require('./bootstrap');


window.Vue = require('vue');
window.moment = require('moment');
Vue.config.devtools = true;

const VueListen = new Vue();
window.VueListen = VueListen;


import TaskList from './components/TaskList.vue';
import LineChart from './components/LineChart.vue';


const app = new Vue({
    el: '#app',
    data: {
        user_id: document.querySelector('meta[name="user-id"]').getAttribute('content'),
        app_name: 'To Do List',
        tasks: [],
        task_name: '',
        no_task: false,
        chart_options: {
            responsive: true,
            maintainAspectRatio: false
        },
        chart_data: {},
        chart_labels: [],
        pending_tasks: [],
        completed_tasks: [],
        task_history: [],
        task_data: [],

    },
    methods: {
        showAddTaskModal(){
            this.task_name = '';
            $('#add-task-modal').modal('show');
        },
        cancelTask(){
            $('#add-task-modal').modal('hide');
        },
        saveTask() {
            axios.post('/api/task', {
                user_id: this.user_id,
                task_name: this.task_name,
                pending_tasks: this.pending_tasks.length + 1,
                completed_tasks: this.completed_tasks.length
              })
              .then((response)=> {
                this.getUserTasks();
              })
              .catch(function (error) {
                console.log(error);
              });
              $('#add-task-modal').modal('hide');

        },
        getUserTasks(){
            axios.get('/api/task/'+this.user_id)
              .then((data)=> {
                    this.tasks = data.data;
                    this.pending_tasks = this.tasks.data.filter((task) => { return task.is_completed == 0 });
                    this.completed_tasks = this.tasks.data.filter((task) => { return task.is_completed == 1 });
                    this.getTaskHistory();
              })
              .catch((error)=> {
                console.log(error);
              });
        },
        RefreshView(){
            this.getUserTasks();
        },
        iniChart(){
            this.chart_labels = [];
            for(let i = 60; i > 0; i-=1 ){
                this.chart_labels.push(i);
            }
            
            this.chart_data = {
                labels: this.chart_labels, // Time
                datasets: [{
                    label: "Pending Tasks",
                    pointRadius: 10,
                    borderColor: 'blue',
                    strokeColor: "red",
                    pointColor: "black",
                    pointBorderWidth: 2,
                    pointBorderColor: "red",
                    data: this.task_data // Tasks
                }]
            }


        },
        getTaskHistory(){
            this.task_data = [];
            axios.post('/api/taskhistory/'+this.user_id)
              .then((response)=> {
                    console.log(response);
                    let taskhistory = response.data.taskhistory;
                    let log_time = new Date(response.data.oldest_log_time);
                    let current_log_time = null;

                    
                    let pending_task = 0;
                    let logExistIndex = 0;
                    for(let i = 60 ; i > 0 ; i--){
                        
                        
                        current_log_time = this.formatDate(log_time);

                        logExistIndex = taskhistory.findIndex((task)=>{ return task.log_time == current_log_time;});
                        
                        if(logExistIndex > -1){
                            
                            
                            pending_task = taskhistory[logExistIndex].pending_tasks;
                            this.task_data.push(pending_task);
                            
                        } else {
                            this.task_data.push(pending_task);
                        }
                        
                        log_time = moment(log_time).add(1, 'm').toDate();
                    }
                   
                    this.iniChart();
                    
              })
              .catch(function (error) {
                console.log(error);
              });
        },
        formatDate(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var fullYear = date.getFullYear();
            var month = date.getMonth()+1;
            var day = date.getDate();
            day = day < 10 ? '0'+day : day;
            month = month < 10 ? '0'+month : month;
            hours = hours < 10 ? '0'+hours : hours;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ':00';
            return fullYear + "-" +month + "-" + day + " " + strTime;
          }

    },
    mounted(){
        this.getUserTasks();

    },
    components:{
        TaskList,
        LineChart
    }
});
