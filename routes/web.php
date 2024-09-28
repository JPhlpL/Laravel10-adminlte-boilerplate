<?php

use App\Http\Controllers\SubtaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Middleware\SecureURL;
use App\Http\Controllers\TaskController; // Update the controller import
use App\Http\Controllers\PermissionController; // Update the controller import
use App\Http\Controllers\RolesController; // Update the controller import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return redirect()->route('login'); });
Auth::routes();


Route::group(['middleware' => 'secure.subtask.urls'], function () {
    //Main
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::view('/create_task', 'create_task')->name('create.task.view');
    Route::post("/create_post_task", [TaskController::class, 'createTask']);
    Route::post("/create_draft_task", [TaskController::class, 'draftTask']);
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/view_task/{taskNumber}', [TaskController::class, 'showTask'])->name('tasks.show');
    Route::post('/display_task', [TaskController::class, 'displayTask']);

    //For Attachment
    Route::get('/attach_table/{sub_taskNumber}', [TaskController::class, 'showAttachTable']);
    Route::get('/delete_attach/{task_attach_name}', [TaskController::class, 'deleteAttach']);


    //Updating Task
    Route::get('/task_todo/{id}', [TaskController::class, 'updateTaskTodo']);
    Route::get('/task_inprogress/{id}', [TaskController::class, 'updateTaskInProgress']);
    Route::get('/task_done/{id}', [TaskController::class, 'updateTaskDone']);

    //Updating Sub Tasks
    Route::get('/sub_todo/{id}', [SubtaskController::class, 'updateSubTodo']);
    Route::get('/sub_inprogress/{id}', [SubtaskController::class, 'updateSubInProgress']);
    Route::get('/sub_done/{id}', [SubtaskController::class, 'updateSubDone']);
    Route::get('/subtasks/{sub_taskNumber}', [SubtaskController::class, 'showSubTask']);

    // Route::get('/dropzone', [TaskController::class, 'dropzone']);
    Route::post('/view_task/{taskNumber}/dropzone/store', [TaskController::class, 'dropzoneStore'])->name('dropzone.store');

    Route::group(['prefix' => 'users', 'as' => 'users.'], function(){
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RolesController::class);
    });

});






