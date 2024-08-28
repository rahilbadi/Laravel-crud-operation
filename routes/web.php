<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('products/create', [ProductController::class, 'store'])->name('products.store');
Route::get('products/{product}edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');


Route::controller(ProductController::class)->group(function(){
    Route::get('/products','index')->name('products.index');
    Route::get('products/create','create')->name('products.create');
    Route::post('products/create','store')->name('products.store');
    Route::get('products/{product}edit','edit')->name('products.edit');
    Route::put('products/{product}','update')->name('products.update');
    Route::delete('/products/{id}','destroy')->name('products.destroy');
});

