<?php

use App\Livewire\CreatePurchase\Index as CreatePurchase;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Warehouses\Index as WarehouseIndex;
use App\Livewire\Warehouses\Edit as WarehouseEdit;
use App\Livewire\Warehouses\Shelves\Create as ShelfCreate;
use App\Livewire\Warehouses\Shelves\Edit as ShelfEdit;
use App\Livewire\Warehouses\Shelves\Levels\Edit\Main as LevelEdit;
use App\Livewire\Purchases\Index as PurchaseIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/productos', ProductIndex::class)->name('products.index')->middleware(['auth']);

Route::get('/products/img/{file}', function($file){
    return Storage::get("products/$file");
})->name('products.img')->middleware(['auth']);

Route::get('/compra', CreatePurchase::class)->name('create-purchase')->middleware(['auth']);

Route::get('/bodegas', WarehouseIndex::class)->name('warehouses.index')->middleware(['auth']);
Route::get('/bodegas/{warehouse}/editar', WarehouseEdit::class)->name('warehouses.edit')->middleware(['auth']);

Route::get('/bodegas/{warehouse}/editar/perchas/crear', ShelfCreate::class)->name('shelves.create')->middleware(['auth']);
Route::get('/perchas/{shelf}/editar', ShelfEdit::class)->name('shelves.edit')->middleware(['auth']);

Route::get('/niveles/{level}/editar', LevelEdit::class)->name('levels.edit')->middleware(['auth']);

Route::get('/compras', PurchaseIndex::class)->name('purchases.index')->middleware(['auth']);
