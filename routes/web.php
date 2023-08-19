<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategotyController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});

 Route::group(['prefix' => 'admin'],function(){

    Route::group(['middleware' => 'admin.guest'], function(){
        Route:: get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route:: post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });
    
    Route::group(['middleware' => 'admin.auth'], function(){

        // Authentication Routes
        Route:: get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route:: get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category Routes
        Route:: get('/categories', [CategotyController::class, 'index'])->name('categories.index');
        Route:: get('/categories/create', [CategotyController::class, 'create'])->name('categories.create');
        Route:: post('/categories', [CategotyController::class, 'store'])->name('categories.store');
        Route:: post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Get Slug
        Route:: get('/getslug', function(Request $req) {
            $slug = '';
            if (!empty($req->title)) {
                $slug = Str::slug($req->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getslug');
    });
 });