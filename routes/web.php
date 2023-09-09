<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\CategotyController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

// Route::get('/', function () {
//     return view('welcome');
// });

 Route::group(['prefix' => 'admin'],function(){

    Route::group(['middleware' => 'admin.guest'], function(){
        Route:: get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route:: post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });
    // Admin Pannel Routes
    Route::group(['middleware' => 'admin.auth'], function(){

        // Authentication Routes
        Route:: get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route:: get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category Routes
        Route:: get('/categories', [CategotyController::class, 'index'])->name('categories.index');
        Route:: get('/categories/create', [CategotyController::class, 'create'])->name('categories.create');
        Route:: post('/categories', [CategotyController::class, 'store'])->name('categories.store');
        Route:: get('/categories/{category}/edit', [CategotyController::class, 'edit'])->name('categories.edit');
        Route:: put('/categories/{category}', [CategotyController::class, 'update'])->name('categories.update');
        Route:: delete('/categories/{category}', [CategotyController::class, 'destroy'])->name('categories.delete');

        // Temp Image
        Route:: post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Sub Category Routes
        Route:: get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route:: get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route:: post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route:: get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route:: put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route:: delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // Brands Routes
        Route:: get('/brands', [BrandsController::class, 'index'])->name('brands.index');
        Route:: get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
        Route:: post('/brands', [BrandsController::class, 'store'])->name('brands.store');
        Route:: get('/brands/{brand}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
        Route:: put('/brands/{brand}', [BrandsController::class, 'update'])->name('brands.update');
        Route:: delete('/brands/{brand}', [BrandsController::class, 'delete'])->name('brands.delete');

        // Products Routes
        Route:: get('/products', [ProductController::class, 'index'])->name('products.index');
        Route:: get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route:: post('/products', [ProductController::class, 'store'])->name('products.store');
        Route:: get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route:: put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route:: delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        Route:: get('/product-sub-categories', [ProductSubCategoryController::class, 'index'])->name('product-sub-categories.index');
        Route:: get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');

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

 // Front end Routes
 Route:: get('/', [FrontController::class, 'index'])->name('front.home');
 Route:: get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
 Route:: get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
 Route:: get('/cart', [CartController::class, 'cart'])->name('front.cart');
 Route:: post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');