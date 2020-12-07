<?php

use Illuminate\Support\Facades\Route;
use App\TaskStatus;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    echo Carbon::now();
    //echo '<br>';
    $taskhistory = TaskStatus::where('user_id',1)
            ->whereDate('created_at', '>=', date(Carbon::now()->subHour()))
            //->whereDate('created_at', '>=', Carbon::now()->subHour())
            ->orderBy('log_time')
            ->get();

    return $taskhistory;        
    
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
