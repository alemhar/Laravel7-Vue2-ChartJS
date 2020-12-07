<?php

namespace App\Http\Controllers\API;

use App\Task;
use Carbon\Carbon;
use App\TaskStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user_id = $request['user_id'];
        $pending_tasks = $request['pending_tasks'];
        $completed_tasks = $request['completed_tasks'];

        
        $this->loghistory($user_id, $pending_tasks, $completed_tasks);  
        
        return Task::create([
            'user_id' => $request['user_id'],
            'task_name' => $request['task_name']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tasks = Task::latest()->paginate(10);
        return $tasks;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $pending_tasks = $request['pending_tasks'];
        $completed_tasks = $request['completed_tasks'];
        
        $task = Task::findOrFail($id);
        $user_id = $task->user_id;
        if($task->is_completed){
            $task->is_completed = 0;
            $pending_tasks = ++$pending_tasks;
            $completed_tasks = --$completed_tasks;
        } else {
            $task->is_completed = 1;
            $pending_tasks = --$pending_tasks;
            $completed_tasks = ++$completed_tasks;
        }
        $task->save();
        $this->loghistory($user_id, $pending_tasks, $completed_tasks);    

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        $pending_tasks = $request['pending_tasks'];
        $completed_tasks = $request['completed_tasks'];
        
        $task = Task::findOrFail($id);
        $user_id = $task->user_id;

        if($task->is_completed){
            --$completed_tasks;
        } else {
            --$pending_tasks;
        }
        $this->loghistory($user_id, $pending_tasks, $completed_tasks);    

        $task->delete();
    }

    public function taskhistory($id)
    {   
        
        
        //$logTimeLessOneHour =  Date(Carbon::now()->subHour());
        $user_id = $id;
        
        $taskhistory = TaskStatus::where('user_id',$user_id)
            ->whereDate('created_at', '>=', Carbon::now()->subHour())
            ->orderBy('log_time')
            ->get();

        $lastest_log_idx = $taskhistory->keys()->last();
        $oldest_log_idx = $taskhistory->keys()->first();

        $oldest_log_time = $taskhistory[$oldest_log_idx]->log_time;
        
        return ['taskhistory' => $taskhistory,'oldest_log_time' => $oldest_log_time];

    }

    public function loghistory($user_id, $pending_tasks, $completed_tasks){

        $logTime = Carbon::now()->format('Y-m-d H:i:'.'00');
        $taskStatus = TaskStatus::firstOrNew(
            ['user_id' => $user_id,'log_time' => $logTime],
            ['pending_tasks' => $pending_tasks, 'completed_tasks' => $completed_tasks]
        );
        if($taskStatus->exists){
            $taskStatus->pending_tasks = $pending_tasks;
            $taskStatus->completed_tasks = $completed_tasks;
        }
        $taskStatus->save();        
    }

}
