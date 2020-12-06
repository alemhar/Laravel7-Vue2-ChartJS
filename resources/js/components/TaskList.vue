<template>
    <div>
            <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Option</th>
                <th scope="col" style="text-align: center;">Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks">
                <th scope="row">{{ task.id}}</th>
                <td>{{ task.task_name }}</td>
                <td>{{ task.is_completed ? 'COMPLETED' : 'PENDING' }}</td>
                <td><a href="#" @click="toggleUserTasks(task.id)" class="card-link">{{ task.is_completed ? 'UNDO' : 'DONE' }}</a></td>
                <td style="text-align: center;"><a href="#" @click="deleteTask(task.id)"  class="text-danger"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
                
            </tbody>
            </table>


    </div>
</template>

<script>
//import Task from './Task.vue'
export default {
  data() {
    return {
        component_name: 'TaskList',
      }
    },
    props: [
            'tasks',
            'pendingTasks',
            'completedTasks'

      ],          
 
  name: 'TaskList',
  methods: {
    deleteTask(id){
      axios.delete('/api/task/'+id,{ data: {
                pending_tasks: this.pendingTasks,
                completed_tasks: this.completedTasks
              }})
      .then((response)=> {
            this.$emit('refresh-view');
      })
      .catch((error)=> {
        console.log(error);
      });          
    },
    toggleUserTasks(id) {
      axios.put('/api/task/'+id,{
                pending_tasks: this.pendingTasks,
                completed_tasks: this.completedTasks
              })
      .then((response)=> {
            this.$emit('refresh-view');
      })
      .catch((error)=> {
        console.log(error);
      });
      
    },

  },
  mounted() {
      //console.log('TaskList Component mounted.')
  }
}
</script>



