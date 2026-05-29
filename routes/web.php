<?php

use App\Http\Controllers\Inventories\StoreController as InventoryStore;
use App\Http\Controllers\Inventories\DownloadController as InventoryDownload;
use App\Http\Controllers\Sales\DownloadController as SaleDownload;
use App\Livewire\CashBoxes\Index as CashBoxIndex;
use App\Livewire\Clients\Index as ClientIndex;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Warehouses\Index as WarehouseIndex;
use App\Livewire\Warehouses\Edit as WarehouseEdit;
use App\Livewire\Warehouses\Shelves\Create as ShelfCreate;
use App\Livewire\Warehouses\Shelves\Edit as ShelfEdit;
use App\Livewire\Warehouses\Shelves\Levels\Edit\Main as LevelEdit;
use App\Livewire\Inventories\Index as InventoryIndex;
use App\Livewire\Inventories\Edit\Main as InventoryEdit;
use App\Livewire\Movements\Purchases\Index as PurchaseIndex;
use App\Livewire\Movements\Purchases\Devolutions\Index as PurchaseDevolutionIndex;
use App\Livewire\Movements\Purchases\Devolutions\Create as PurchaseDevolutionCreate;
use App\Livewire\Movements\Purchases\Devolutions\Edit as PurchaseDevolutionEdit;
use App\Livewire\Movements\Purchases\Create as PurchaseCreate;
use App\Livewire\Movements\Purchases\Edit as PurchaseEdit;
use App\Livewire\Products\Kardex;
use App\Livewire\Providers\Index as ProviderIndex;
use App\Livewire\Sales\Index\Main as SaleIndex;
use App\Livewire\Sales\Day\Main as SaleDay;
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

Route::middleware(['auth', 'can:products'])->group(function(){
    Route::get('/productos', ProductIndex::class)->name('products.index');

    Route::get('/productos/img/{file}', function($file){
        return Storage::get("products/$file");
    })->name('products.img');

    Route::get('/productos/{product}/kardex', Kardex::class)->name('products.kardex');;
});

Route::middleware(['auth', 'can:warehouses'])->group(function(){
    Route::get('/bodegas', WarehouseIndex::class)->name('warehouses.index');
    Route::get('/bodegas/{warehouse}/editar', WarehouseEdit::class)->name('warehouses.edit');
    Route::get('/bodegas/{warehouse}/editar/perchas/crear', ShelfCreate::class)->name('shelves.create');
    Route::get('/perchas/{shelf}/editar', ShelfEdit::class)->name('shelves.edit');
    Route::get('/niveles/{level}/editar', LevelEdit::class)->name('levels.edit');
});

Route::middleware(['auth', 'can:inventories'])->group(function(){
    Route::get('/inventarios', InventoryIndex::class)->name('inventories.index');
    Route::post('/inventarios', InventoryStore::class)->name('inventories.store');
    Route::get('/inventarios/{inventory}/editar', InventoryEdit::class)->name('inventories.edit');
    Route::get('/inventarios/{inventory}/descargar', InventoryDownload::class)->name('inventories.download');
});

Route::middleware(['auth', 'can:sales'])->group(function(){
    Route::get('/ventas', SaleIndex::class)->name('sales.index');
    Route::get('/ventas/descargar', [SaleDownload::class, 'single'])->name('sales.download-single');
    Route::get('/ventas/descargar/multiples', [SaleDownload::class, 'multiple'])->name('sales.download-multiple');
    Route::livewire('/ventas/{warehouse}/dia', SaleDay::class)->name('sales.day');
});

Route::middleware(['auth', 'can:cash-boxes'])->get('/cajas', CashBoxIndex::class)->name('cash-boxes.index');

Route::middleware(['auth', 'can:providers'])->get('/proveedores', ProviderIndex::class)->name('providers.index');

Route::middleware(['auth', 'can:purchases'])->group(function() {
    Route::get('/compras', PurchaseIndex::class)->name('purchases.index');
    Route::get('/compras/crear', PurchaseCreate::class)->name('purchases.create');
    Route::get('/compras/{purchase}/editar', PurchaseEdit::class)->name('purchases.edit');

    Route::get('/devoluciones-compras', PurchaseDevolutionIndex::class)->name('purchase-devolutions.index');
    Route::get('/devoluciones-compras/crear', PurchaseDevolutionCreate::class)->name('purchase-devolutions.create');
    Route::get('/devoluciones-compras/{purchaseDevolution}/editar', PurchaseDevolutionEdit::class)->name('purchase-devolutions.edit');
});

Route::middleware(['auth', 'can:clients'])->get('/clientes', ClientIndex::class)->name('clients.index');