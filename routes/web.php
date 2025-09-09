<?php

use App\Http\Controllers\Inventories\StoreController as InventoryStore;
use App\Http\Controllers\Inventories\DownloadController as InventoryDownload;
use App\Http\Controllers\Sales\CalcController as SaleCalc;
use App\Http\Controllers\Sales\DownloadController as SaleDownload;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Warehouses\Index as WarehouseIndex;
use App\Livewire\Warehouses\Edit as WarehouseEdit;
use App\Livewire\Warehouses\Shelves\Create as ShelfCreate;
use App\Livewire\Warehouses\Shelves\Edit as ShelfEdit;
use App\Livewire\Warehouses\Shelves\Levels\Edit\Main as LevelEdit;
use App\Livewire\Movements\Index as MovementIndex;
use App\Livewire\Movements\Create\Main as MovementCreate;
use App\Livewire\Inventories\Index as InventoryIndex;
use App\Livewire\Inventories\Edit\Main as InventoryEdit;
use App\Livewire\Sales\Index\Main as SaleIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('configuraciones', 'configuraciones/perfil');

    Route::get('configuraciones/perfil', Profile::class)->name('settings.profile');
    Route::get('configuraciones/contraseña', Password::class)->name('settings.password');
    Route::get('configuraciones/apariencia', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/productos', ProductIndex::class)->name('products.index')->middleware(['auth']);

Route::get('/products/img/{file}', function($file){
    return Storage::get("products/$file");
})->name('products.img')->middleware(['auth']);

Route::get('/bodegas', WarehouseIndex::class)->name('warehouses.index')->middleware(['auth']);
Route::get('/bodegas/{warehouse}/editar', WarehouseEdit::class)->name('warehouses.edit')->middleware(['auth']);

Route::get('/bodegas/{warehouse}/editar/perchas/crear', ShelfCreate::class)->name('shelves.create')->middleware(['auth']);
Route::get('/perchas/{shelf}/editar', ShelfEdit::class)->name('shelves.edit')->middleware(['auth']);

Route::get('/niveles/{level}/editar', LevelEdit::class)->name('levels.edit')->middleware(['auth']);

Route::get('/movimientos', MovementIndex::class)->name('movements.index')->middleware(['auth']);
Route::get('/movimientos/crear', MovementCreate::class)->name('movements.create')->middleware(['auth']);

Route::get('/inventarios', InventoryIndex::class)->name('inventories.index')->middleware(['auth']);
Route::post('/inventarios', InventoryStore::class)->name('inventories.store')->middleware(['auth']);
Route::get('/inventarios/{inventory}/editar', InventoryEdit::class)->name('inventories.edit')->middleware(['auth']);
Route::get('/inventarios/{inventory}/descargar', InventoryDownload::class)->name('inventories.download')->middleware(['auth']);
Route::get('/ventas', SaleIndex::class)->name('sales.index')->middleware(['auth']);
Route::get('/ventas/calcular', SaleCalc::class)->name('sales.calc')->middleware(['auth']);
Route::get('/ventas/descargar', SaleDownload::class)->name('sales.download')->middleware(['auth']);