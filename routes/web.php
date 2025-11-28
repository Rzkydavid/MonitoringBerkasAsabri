<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePrivilegeController;
use App\Http\Controllers\RoleMenuController;
use App\Http\Controllers\StatusBerkasController;


// Public: Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');


Route::get('/debug/populate-privileges', [PrivilegeController::class, 'populate'])
	->name('privileges.populate')
	->withoutMiddleware(['auth', 'role_privilege']);

// Protected routes: dashboard/home and any other pages
Route::middleware('auth')->group(function () {

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


	Route::get('/roles', [RoleController::class, 'index'])
		->name('roles.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE');

	Route::get('/roles/data', [RoleController::class, 'data'])
		->name('roles.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE');

	Route::get('/roles/create', [RoleController::class, 'create'])
		->name('roles.create')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_ROLE');

	Route::post('/roles/store', [RoleController::class, 'store'])
		->name('roles.store')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_ROLE');

	Route::post('/roles/bulk-delete', [RoleController::class, 'bulkDelete'])
		->name('roles.bulk-delete')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'DELETE_ROLE');

	Route::get('/roles/{encoded}/edit', [RoleController::class, 'edit'])
		->name('roles.edit')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_ROLE');

	Route::put('/roles/{encoded}/update', [RoleController::class, 'update'])
		->name('roles.update')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_ROLE');

	Route::get('/privileges', [PrivilegeController::class, 'index'])
		->name('privileges.index')
		->middleware('auth', 'privilege')
		->defaults('privilege', 'VIEW_PRIVILEGE');

	Route::get('/privileges/data', [PrivilegeController::class, 'data'])
		->name('privileges.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_PRIVILEGE');

	Route::get('/privileges/populate', [PrivilegeController::class, 'populate'])
		->name('privileges.populate')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'POPULATE_PRIVILEGE');

	Route::get('/privileges/available', [PrivilegeController::class, 'available'])
		->name('privileges.available')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_AVAILABLE_PRIVILEGE');

	Route::get('/roles-privileges', [RolePrivilegeController::class, 'index'])
		->name('roles-privileges.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE_PRIVILEGE');

	Route::get('/roles-privileges/data', [RolePrivilegeController::class, 'data'])
		->name('roles-privileges.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE_PRIVILEGE');

	Route::post('/roles-privileges/assign', [RolePrivilegeController::class, 'assign'])
		->name('roles-privileges.assign')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'ASSIGN_ROLE_PRIVILEGE');

	Route::post('/roles-privileges/bulk-delete', [RolePrivilegeController::class, 'bulkDelete'])
		->name('roles-privileges.bulk-delete')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'DELETE_ROLE_PRIVILEGE');

	Route::get('/roles-menus', [RoleMenuController::class, 'index'])
		->name('roles-menus.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE_MENU');

	Route::get('/roles-menus/data', [RoleMenuController::class, 'data'])
		->name('roles-menus.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_ROLE_MENU');

	Route::get('/roles-menus/create', [RoleMenuController::class, 'create'])
		->name('roles-menus.create')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_ROLE_MENU');

	Route::post('/roles-menus/store', [RoleMenuController::class, 'store'])
		->name('roles-menus.store')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_ROLE_MENU');

	Route::post('/roles-menus/bulk-delete', [RoleMenuController::class, 'bulkDelete'])
		->name('roles-menus.bulk-delete')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'DELETE_ROLE_MENU');

	Route::get('/roles-menus/{encoded}/edit', [RoleMenuController::class, 'edit'])
		->name('roles-menus.edit')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_ROLE_MENU');

	Route::put('/roles-menus/{encoded}/update', [RoleMenuController::class, 'update'])
		->name('roles-menus.update')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_ROLE_MENU');

	Route::get('/status-berkas', [StatusBerkasController::class, 'index'])
		->name('status-berkas.index')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_STATUS_BERKAS');

	Route::get('/status-berkas/data', [StatusBerkasController::class, 'data'])
		->name('status-berkas.data')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'VIEW_STATUS_BERKAS');

	Route::get('/status-berkas/create', [StatusBerkasController::class, 'create'])
		->name('status-berkas.create')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_STATUS_BERKAS');

	Route::post('/status-berkas/store', [StatusBerkasController::class, 'store'])
		->name('status-berkas.store')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'CREATE_STATUS_BERKAS');

	Route::post('/status-berkas/bulk-delete', [StatusBerkasController::class, 'bulkDelete'])
		->name('status-berkas.bulk-delete')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'DELETE_STATUS_BERKAS');

	Route::get('/status-berkas/{encoded}/edit', [StatusBerkasController::class, 'edit'])
		->name('status-berkas.edit')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_STATUS_BERKAS');

	Route::put('/status-berkas/{encoded}/update', [StatusBerkasController::class, 'update'])
		->name('status-berkas.update')
		->middleware(['auth', 'privilege'])
		->defaults('privilege', 'UPDATE_STATUS_BERKAS');



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
