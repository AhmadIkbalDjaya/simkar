<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Mutations;
use App\Livewire\Profile;
use App\Livewire\Reports;
use App\Livewire\Rooms;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserIndex;
use App\Livewire\Wbps;
use App\Models\Inmate;
use App\Models\RoomTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::model('wbp', Inmate::class);
Route::model('mutation', RoomTransfer::class);

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/dashboard', Dashboard::class)
        ->middleware('role:ADMIN,OFFICER')
        ->name('dashboard');

    Route::get('/rooms', Rooms\Index::class)
        ->middleware('role:ADMIN,OFFICER')
        ->name('rooms.index');
    Route::get('/rooms/create', Rooms\Create::class)
        ->middleware('role:ADMIN')
        ->name('rooms.create');
    Route::get('/rooms/{room}', Rooms\Show::class)
        ->middleware('role:ADMIN,OFFICER')
        ->name('rooms.show');
    Route::get('/rooms/{room}/edit', Rooms\Edit::class)
        ->middleware('role:ADMIN')
        ->name('rooms.edit');

    Route::get('/wbps', Wbps\Index::class)
        ->middleware('role:ADMIN,OFFICER')
        ->name('wbps.index');
    Route::get('/wbps/create', Wbps\Create::class)
        ->middleware('role:ADMIN')
        ->name('wbps.create');
    Route::get('/wbps/{wbp}', Wbps\Show::class)
        ->middleware('role:ADMIN,OFFICER')
        ->name('wbps.show');
    Route::get('/wbps/{wbp}/edit', Wbps\Edit::class)
        ->middleware('role:ADMIN')
        ->name('wbps.edit');

    Route::middleware('role:ADMIN,OFFICER')->group(function () {
        Route::get('/mutations', Mutations\Index::class)->name('mutations.index');
        Route::get('/mutations/create', Mutations\Create::class)->name('mutations.create');
        Route::get('/mutations/{mutation}', Mutations\Show::class)->name('mutations.show');

        Route::get('/reports/mutations', Reports\MutationReport::class)->name('reports.mutations');
    });

    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');
    });

    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil. Sampai jumpa!');
    })->name('logout');
});
