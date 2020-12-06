<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = [
        'user_id', 
        'pending_tasks',
        'completed_tasks',
        'log_time'
     ];

     protected $table = 'task_status'; 
}
