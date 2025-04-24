<?php

use App\Livewire\CreatePurchase\Index as CreatePurchase;
use App\Livewire\Products\Index as ProductIndex;
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
