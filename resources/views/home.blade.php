<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'To Do List') }}</title>

    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ddbedb996e.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @if (Auth::check())
    <meta name="user-id" content="{{ Auth::user()->id }}" />
    @endif
</head>
<body>
    <div id="app" class="mb-4">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/todoist.png') }}" style="width:150px;" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

            
        <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title">Burndown Chart : Last 60 Minutes Pending Tasks</h5>
                        
                        <line-chart :chart-data="chart_data" :options="chart_options" :width="1200":height="400"></line-chart>
                    </div>
                </div>
            </div>
            
        </div>    
        <div class="row pt-4">
            <div class="col-md-6">
            
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8">
                                <h5 class="card-title">Pending List</h5>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-success float-right" @click="showAddTaskModal">+New</button>
                            </div>

                            
                        </div>
                        <task-list :tasks="pending_tasks" v-on:refresh-view="RefreshView" :pending-tasks="pending_tasks.length" :completed-tasks="completed_tasks.length"></task-list>
                                                    
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8">
                                <h5 class="card-title">Completed List</h5>
                            </div>
                            
                        </div>
                        <task-list :tasks="completed_tasks" v-on:refresh-view="RefreshView" :pending-tasks="pending_tasks.length" :completed-tasks="completed_tasks.length"></task-list>
                                                    
                    </div>
                </div>
                

            </div>
        </div>

        </div>


        <!-- Add Task Modal 
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        *
        -->

        <div class="modal fade" id="add-task-modal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
            
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="addNewLabel">Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                
                
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Description</span>
                    </div>
                    <input v-model="task_name" type="text" name="task_name" class="form-control">
                </div>
                <div class="input-group mb-2">
                    <p v-show="no_task" class="empty-field-message">** Please enter Description.</p>
                </div>  
                </div>
                <div class="modal-footer">
                <button type="button" @click="cancelTask" class="btn btn-danger">Cancel</button>
                <button type="button" @click="saveTask" class="btn btn-success">Save</button>
                </div>

            </div>
            </div>
            
        </div>
        
        
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
