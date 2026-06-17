<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Rooms;
use App\Livewire\Wbps;
use App\Models\Inmate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::model('wbp', Inmate::class);

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/rooms', Rooms\Index::class)->name('rooms.index');
    Route::get('/rooms/create', Rooms\Create::class)->name('rooms.create');
    Route::get('/rooms/{room}', Rooms\Show::class)->name('rooms.show');
    Route::get('/rooms/{room}/edit', Rooms\Edit::class)->name('rooms.edit');

    Route::get('/wbps', Wbps\Index::class)->name('wbps.index');
    Route::get('/wbps/create', Wbps\Create::class)->name('wbps.create');
    Route::get('/wbps/{wbp}', Wbps\Show::class)->name('wbps.show');
    Route::get('/wbps/{wbp}/edit', Wbps\Edit::class)->name('wbps.edit');

    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    })->name('logout');
});
