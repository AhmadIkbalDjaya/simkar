<?php

use App\Http\Controllers\MutationQrCodeController;
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

    // Note: static segments (/create) are declared before /{param} routes so they
    // are not captured as route model parameters.
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', Rooms\Index::class)->middleware('role:ADMIN,OFFICER')->name('index');
        Route::get('/create', Rooms\Create::class)->middleware('role:ADMIN')->name('create');
        Route::get('/{room}', Rooms\Show::class)->middleware('role:ADMIN,OFFICER')->name('show');
        Route::get('/{room}/edit', Rooms\Edit::class)->middleware('role:ADMIN')->name('edit');
    });

    Route::prefix('wbps')->name('wbps.')->group(function () {
        Route::get('/', Wbps\Index::class)->middleware('role:ADMIN,OFFICER')->name('index');
        Route::get('/create', Wbps\Create::class)->middleware('role:ADMIN')->name('create');
        Route::get('/{wbp}', Wbps\Show::class)->middleware('role:ADMIN,OFFICER')->name('show');
        Route::get('/{wbp}/edit', Wbps\Edit::class)->middleware('role:ADMIN')->name('edit');
    });

    Route::middleware('role:ADMIN,OFFICER')->group(function () {
        Route::prefix('mutations')->name('mutations.')->group(function () {
            Route::get('/', Mutations\Index::class)->name('index');
            Route::get('/create', Mutations\Create::class)->name('create');
            Route::get('/qr-code', [MutationQrCodeController::class, 'image'])->name('qr.image');
            Route::get('/qr-code/print', [MutationQrCodeController::class, 'print'])->name('qr.print');
            Route::get('/{mutation}', Mutations\Show::class)->name('show');
        });

        Route::get('/reports/mutations', Reports\MutationReport::class)->name('reports.mutations');
    });

    Route::middleware('role:ADMIN')->prefix('users')->name('users.')->group(function () {
        Route::get('/', UserIndex::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{user}/edit', UserEdit::class)->name('edit');
    });

    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil. Sampai jumpa!');
    })->name('logout');
});
