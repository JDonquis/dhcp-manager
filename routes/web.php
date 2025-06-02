<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', [AppController::class, 'welcome'])->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.post');


Route::middleware(['auth'])->prefix('admin')->group(function () 
{
    Route::get('/', [AppController::class, 'admin'])->name('home');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    Route::post('/dhcp-generate', [AppController::class, 'generateDHCP'])->name('generate-dhcp');
    Route::post('/dhcp-reload', [AppController::class, 'reloadDHCP'])->name('reload-dhcp');
    Route::get('/download-dhcp/{file}', [AppController::class,'downloadDHCP'])->name('download-dhcp-file');
     
    // ----------------------- Hosts
    Route::get('/hosts', [HostController::class, 'index'])->name('hosts.index');
    Route::get('/hosts/datatable', [HostController::class, 'data'])->name('hosts.data');
    Route::get('/hosts/crear', [HostController::class, 'create'])->name('hosts.create');
    Route::post('/hosts/crear', [HostController::class, 'store'])->name('hosts.store');
    Route::get('/hosts/editar/{host}', [HostController::class, 'edit'])->name('hosts.edit');
    Route::put('/hosts/editar/{host}', [HostController::class, 'update'])->name('hosts.update');
    Route::delete('/hosts/editar/{host}', [HostController::class, 'destroy'])->name('hosts.destroy');




    // ----------------------- Departamentos
    Route::get('/departamentos', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departamentos/datatable', [DepartmentController::class, 'data'])->name('departments.data');
    Route::get('/departamentos/crear', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departamentos/crear', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departamentos/editar/{department}', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departamentos/editar/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departamentos/editar/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    
    // ----------------------- Grupos
    Route::get('/grupos', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/grupos/datatable', [GroupController::class, 'data'])->name('groups.data');
    Route::get('/grupos/crear', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/grupos/crear', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/grupos/editar/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/grupos/editar/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/grupos/editar/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    
    // ----------------------- Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/datatable', [UserController::class, 'data'])->name('users.data');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios/crear', [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/editar/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/editar/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/editar/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // ----------------------- Configuracion
    Route::get('/configuracion', [NetworkController::class, 'edit'])->name('configuracion.index');
    Route::put('/configuracion', [NetworkController::class, 'update'])->name('configuracion.update');


    // Notificaciones
    Route::prefix('notifications')->group(function() {
        
        Route::post('/{id}/mark-as-read', [NotificationController::class,'markRead'])->name('notifications.mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class,'markAllRead'])->name('notifications.mark-all-read');

        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    });


});