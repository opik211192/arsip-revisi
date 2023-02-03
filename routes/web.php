<?php

use App\Models\User;
use App\Models\Struktur;
use App\Models\Struktural;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StrukturalController;
use App\Http\Controllers\Arsip\ArsipController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisArsipController;
use App\Http\Controllers\Permissions\RoleController;
use App\Http\Controllers\Permissions\UserController;
use App\Http\Controllers\Permissions\AssignController;
use App\Http\Controllers\Permissions\PermissionController;

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
    //return view('welcome');
    return redirect()->route('login');

});


//route filter utk percobaan
Route::get('coba', [CobaController::class, 'index'])->name('coba.index');

//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Auth::routes();
Auth::routes(['register' => false]);

Route::get('/user/data', [UserDataController::class, 'index'])->name('user.data');
Route::get('/user/create', [UserDataController::class, 'create'])->name('user.create');
Route::post('/user/create', [UserDataController::class, 'store'])->name('user.store');
Route::get('/user/{user}', [UserDataController::class, 'edit'])->name('user.edit');
Route::put('/user/{user}', [UserDataController::class, 'update'])->name('user.update');
Route::delete('/user/{user}', [UserDataController::class, 'delete'])->name('user.delete');

Route::post('/user/alldata', [RegisterController::class,'ambilData'])->name('ambilData');

// /Route::post('/register/user', [RegisterController::class,'ambilData'])->name('ambilData');



Route::middleware('has.role')->prefix('xyz')->group(function(){
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    //Route untuk arsip
    Route::prefix('arsip')->group(function(){
        Route::get('', [ArsipController::class, 'index'])->name('arsip.index');
        Route::post('', [ArsipController::class, 'store'])->name('arsip.store');
        Route::get('/data', [ArsipController::class, 'data'])->name('arsip.data');
        Route::get('/data/{arsip}/detail', [ArsipController::class, 'detail'])->name('arsip.detail');
        Route::get('/data/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');
        Route::get('/edit/{arsip}', [ArsipController::class, 'edit'])->name('arsip.edit');
        Route::put('edit/{arsip}', [ArsipController::class, 'update']);
        Route::delete('/data/{arsip}', [ArsipController::class, 'destroy'])->name('arsip.delete');
    });

    //Route untuk setting jenis arsip
    Route::prefix('setting-jenis-klasifikasi')->group(function(){
        Route::get('', [JenisController::class, 'index'])->name('jenis.index');
        Route::post('/create', [JenisController::class, 'store'])->name('jenis.create');
        Route::get('/{jenis}/edit', [JenisController::class, 'edit'])->name('jenis.edit');
        Route::put('{jenis}/edit', [JenisController::class, 'update']);
        Route::delete('/{jenis}', [JenisController::class, 'delete'])->name('jenis.delete');
    });

    Route::prefix('setting-jenis-arsip')->group(function(){
        Route::get('', [JenisArsipController::class, 'index'])->name('jenis_arsip.index');
        Route::post('/create', [JenisArsipController::class, 'store'])->name('jenis_arsip.create');
        Route::get('/{jenisArsip}/edit', [JenisArsipController::class, 'edit'])->name('jenis_arsip.edit');
        Route::put('{jenisArsip}/edit', [JenisArsipController::class, 'update']);
        Route::delete('/{jenisArsip}', [JenisArsipController::class, 'delete'])->name('jenis_arsip.delete');
    });

    //Route untuk setting Unit user
    Route::prefix('setting-unit-user')->group(function(){
        Route::get('', [UnitController::class, 'index'])->name('unit.index');
        Route::post('/create', [UnitController::class, 'store'])->name('unit.create');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('unit.edit');
        Route::put('{unit}/edit', [UnitController::class, 'update']);
        Route::delete('/{unit}', [UnitController::class, 'delete'])->name('unit.delete');
    });

    Route::prefix('setting-struktural')->group(function(){
        //ini untuk sub struktural
        Route::get('', [StrukturalController::class, 'index'])->name('struktural.index');
        Route::get('/create', [StrukturalController::class, 'create'])->name('struktural.create');
        Route::post('/create', [StrukturalController::class, 'store']);
        Route::get('/{struktural_detail}/edit', [StrukturalController::class, 'edit'])->name('struktural.edit');
        Route::put('/{struktural_detail}/edit', [StrukturalController::class, 'update']);
        Route::delete('/{struktural_detail}', [StrukturalController::class, 'delete'])->name('struktural.delete');

        //ini utnuk struktural
        Route::get('/create-struktural', [StrukturalController::class, 'create_struktural'])->name('struktural_create.create');
        Route::post('/create-struktural', [StrukturalController::class, 'store_struktural']);
        Route::get('/create-struktural/{struktural}/edit', [StrukturalController::class, 'edit_struktural'])->name('struktural_create.edit');
        Route::put('/create-struktural/{struktural}/edit', [StrukturalController::class, 'update_struktural']);
        Route::delete('/create-struktural/{struktural}', [StrukturalController::class, 'delete_struktural'])->name('struktural_create.delete');
    });

    Route::prefix('role-and-permission')->namespace('Permissions')->middleware('permission:assign permission')->group(function(){

        
        
        //Route untuk Assign Permission
        Route::get('assignable', [AssignController::class, 'create'])->name('assign.create');
        Route::post('assignable', [AssignController::class, 'store']);
        Route::get('assignable/{role}/edit', [AssignController::class, 'edit'])->name('assign.edit');
        Route::put('assignable/{role}/edit', [AssignController::class, 'update']);


        //Route untuk permisiion to user 
        Route::get('assign/user', [UserController::class, 'create'])->name('assign.user.create');
        Route::post('assign/user', [UserController::class, 'store']);
        Route::get('assign/{user}/user', [UserController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign/{user}/user', [UserController::class, 'update']);


        //Route untuk Roles
        Route::prefix('roles')->group(function(){
            Route::get('', [RoleController::class, 'index'])->name('roles.index');
            Route::post('/create', [RoleController::class, 'store'])->name('roles.create');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/{role}/edit', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'delete'])->name('roles.delete');         
        });

        //Route untuk Permissions
        Route::prefix('permissions')->group(function(){
            Route::get('', [PermissionController::class, 'index'])->name('permissions.index');
            Route::post('/create', [PermissionController::class, 'store'])->name('permissions.create');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
            Route::put('/{permission}/edit', [PermissionController::class, 'update']);
            Route::delete('/{permission}', [PermissionController::class, 'delete'])->name('permissions.delete');         
        });

    });
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
