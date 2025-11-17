<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;

// Public: Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');


Route::get('/debug/populate-privileges', [PrivilegeController::class, 'populate'])
	->name('privileges.populate')
	->withoutMiddleware(['auth', 'role_privilege']);

// Protected routes: dashboard/home and any other pages
Route::middleware('auth')->group(function () {

	Route::get('/privileges', [PrivilegeController::class, 'index'])
		->middleware('auth', 'privilege')
		->name('privileges.index');

	Route::get('/unauthorized', function () {
		return view('errors.unauthorized');
	})->name('unauthorized');

	Route::get('/', [DashboardController::class, 'index'])
		->name('dashboard')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_DASHBOARD');

	Route::get('/menus', [MenuController::class, 'index'])
		->name('menus.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_MENU');

	Route::get('/menus/data', [MenuController::class, 'data'])
		->name('menus.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_MENU');

	Route::get('/menus/create', [MenuController::class, 'create'])
		->name('menus.create')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_MENU');

	Route::post('/menus/store', [MenuController::class, 'store'])
		->name('menus.store')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_MENU');

	Route::post('/menus/bulk-delete', [MenuController::class, 'bulkDelete'])
		->name('menus.bulk-delete')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'DELETE_MENU');

	Route::get('/menus/{encoded}/edit', [MenuController::class, 'edit'])
		->name('menus.edit')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_MENU');

	Route::put('/menus/{encoded}/update', [MenuController::class, 'update'])
		->name('menus.update')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_MENU');

	Route::get('/role-menu', [MenuController::class, 'index'])
		->name('role-menu.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE_MENU');



	// Route::get('/', function () {
	//     return view('dashboard.index');
	// })->name('dashboard');

	// Example: a protected page
	// Route::get('/profile', function () {
	//     return view('profile');
	// })->name('profile');

	// logout
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Route::get('/', function () {return redirect('sign-in');})->middleware('guest');
// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
// Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
// Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
// Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify');
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('billing', function () {
		return view('pages.billing');
	})->name('billing');
	Route::get('tables', function () {
		return view('pages.tables');
	})->name('tables');
	Route::get('rtl', function () {
		return view('pages.rtl');
	})->name('rtl');
	Route::get('virtual-reality', function () {
		return view('pages.virtual-reality');
	})->name('virtual-reality');
	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');
	Route::get('static-sign-in', function () {
		return view('pages.static-sign-in');
	})->name('static-sign-in');
	Route::get('static-sign-up', function () {
		return view('pages.static-sign-up');
	})->name('static-sign-up');
	Route::get('user-management', function () {
		return view('pages.laravel-examples.user-management');
	})->name('user-management');
	Route::get('user-profile', function () {
		return view('pages.laravel-examples.user-profile');
	})->name('user-profile');
});
