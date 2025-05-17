<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\WorkoutLibraryController;

Route::match(['get', 'post'], '/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Placeholder dashboard routes:
Route::middleware(['auth', 'role:admin'])->get('/dashboard/admin', fn () => view('dashboard.admin'))->name('dashboard.admin');
Route::middleware(['auth', 'role:trainer'])->get('/dashboard/trainer', fn () => view('dashboard.trainer'))->name('dashboard.trainer');
Route::middleware(['auth', 'role:user'])->get('/dashboard/user', fn () => view('dashboard.user'))->name('dashboard.user');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/assign-clients', [UserController::class, 'assignClientsForm'])->name('admin.assign.clients');
    Route::post('/admin/assign-clients', [UserController::class, 'assignClients'])->name('admin.assign.clients.submit');
});


Route::middleware(['auth', 'role:admin,trainer'])->group(function () {
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::post('/workouts/store', [WorkoutController::class, 'store'])->name('workouts.store');
});

Route::get('/calendar-events', [\App\Http\Controllers\CalendarController::class, 'events'])->middleware('auth');

Route::middleware(['auth', 'role:admin'])->get('/dashboard/admin', fn () => view('dashboard.admin'))->name('dashboard.admin');


Route::get('/users', [UserController::class, 'index'])->name('users.index');
//Route::get('/assign-clients', [UserController::class, 'assignClientsForm'])->name('assign.clients.form');
Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');



// Resourceful routes for user management
Route::prefix('users')->name('users.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
});

Route::get('/admin/workout-library', [App\Http\Controllers\Admin\WorkoutLibraryController::class, 'index'])->name('admin.workout.library');
Route::post('/admin/workout-library/category', [App\Http\Controllers\Admin\WorkoutLibraryController::class, 'addCategory'])->name('admin.add.category');
Route::post('/admin/workout-library/type', [App\Http\Controllers\Admin\WorkoutLibraryController::class, 'addType'])->name('admin.add.type');


// ✏️ Category
Route::put('/admin/workout-library/category/{id}', [WorkoutLibraryController::class, 'updateCategory'])->name('admin.category.update');
Route::delete('/admin/workout-library/category/{id}', [WorkoutLibraryController::class, 'deleteCategory'])->name('admin.category.delete');

// ✏️ Type
Route::put('/admin/workout-library/type/{id}', [WorkoutLibraryController::class, 'updateType'])->name('admin.type.update');
Route::delete('/admin/workout-library/type/{id}', [WorkoutLibraryController::class, 'deleteType'])->name('admin.type.delete');
