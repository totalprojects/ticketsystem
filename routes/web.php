<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect()->to('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home');

/** All Views */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'permissions']], function () {

    Route::get('/change-password', [App\Http\Controllers\User\UserController::class, 'changePasswordPage'])->name('user.change.password');

    Route::get('/users', [App\Http\Controllers\User\UserController::class, 'index'])->name('users.view');

    Route::get('/roles', [App\Http\Controllers\Role\RoleController::class, 'index'])->name('roles.view');

    Route::get('/menus', [App\Http\Controllers\Menu\MenuController::class, 'index'])->name('menus.view');

    Route::get('/modules', [App\Http\Controllers\Permission\PermissionController::class, 'index'])->name('permissions.view');

    Route::get('/employees', [App\Http\Controllers\Employee\EmployeeController::class, 'index'])->name('employees.view');

    Route::get('/tree', [App\Http\Controllers\TreeController::class, 'index'])->name('tree.view');

    Route::get('/sap-request', [App\Http\Controllers\Request\SapController::class, 'index'])->name('sap.view');

    Route::get('/email-request', [App\Http\Controllers\Request\EmailController::class, 'index'])->name('email.view');

});

/** Ajax Calls */
Route::group(['prefix' => 'ajax'], function () {

    /* Reads */

    Route::get('/users-list/{token}', [App\Http\Controllers\User\UserController::class, 'fetchUsers'])->name('users.list');

    Route::get('menu/list', [App\Http\Controllers\Menu\MenuController::class, 'fetchMenus'])->name('get.menu.list');

    Route::get('role/list', [App\Http\Controllers\Role\RoleController::class, 'fetchRoles'])->name('get.roles.list');

    Route::get('role/permissions', [App\Http\Controllers\Role\RoleController::class, 'fetchRolePermissions'])->name('show.role.permissions');

    Route::get('/permissions-list', [App\Http\Controllers\Permission\PermissionController::class, 'fetchPermissions'])->name('show.permissions');

    Route::get('/trash-permission', [App\Http\Controllers\Permission\PermissionController::class, 'trashTcode'])->name('trash.tcode');

    Route::get('/employee-list/{token}', [App\Http\Controllers\Employee\EmployeeController::class, 'fetchEmployees'])->name('fetch.employees');

    Route::get('/states/list', [App\Http\Controllers\Area\AreaController::class, 'fetchStates'])->name('fetch.states');

    Route::get('/districts/list', [App\Http\Controllers\Area\AreaController::class, 'fetchDistricts'])->name('fetch.districts');

    Route::get('/companies/list', [App\Http\Controllers\Employee\EmployeeController::class, 'fetchCompanies'])->name('fetch.companies');

    Route::get('/departments/list', [App\Http\Controllers\Employee\EmployeeController::class, 'fetchDepartments'])->name('fetch.departments');

    Route::get('/roles/list', [App\Http\Controllers\Employee\EmployeeController::class, 'fetchRoles'])->name('fetch.roles');

    Route::get('/report-to/list', [App\Http\Controllers\Employee\EmployeeController::class, 'fetchReportTos'])->name('fetch.report_tos');

    Route::get('/plant/list', [App\Http\Controllers\Plant\PlantController::class, 'getPlants'])->name('get.plants');

    Route::get('/storage/list', [App\Http\Controllers\Storage\StorageController::class, 'getStorages'])->name('get.storages');

    Route::get('/sales_office/list', [App\Http\Controllers\SO\SoController::class, 'getSalesOffice'])->name('get.sales_office');

    Route::get('/temp', [App\Http\Controllers\SO\SoController::class, 'set_so_id'])->name('x.temp');

    /* HTTP CUD */
    Route::post('menu/add', [App\Http\Controllers\Menu\MenuController::class, 'addMenu'])->name('add.menu');

    Route::post('menu/reorder', [App\Http\Controllers\Menu\MenuController::class, 'reorderMenu'])->name('menu.reorder');

    Route::post('update/user/permission', [App\Http\Controllers\User\UserController::class, 'updatePermissions'])->name('update.user.permissions');

    Route::post('update/user/role', [App\Http\Controllers\User\UserController::class, 'updateRole'])->name('update.user.roles');

    Route::post('update/user/menus', [App\Http\Controllers\User\UserController::class, 'updateMenus'])->name('update.user.menus');

    Route::post('/update-menu', [App\Http\Controllers\Menu\MenuController::class, 'updateMenu'])->name('update.menu');

    Route::post('/update-permission', [App\Http\Controllers\Permission\PermissionController::class, 'updatePermission'])->name('update.permission');

    Route::post('/add-permission', [App\Http\Controllers\Permission\PermissionController::class, 'addPermission'])->name('add.permission');

    Route::post('/add-tcode', [App\Http\Controllers\Permission\PermissionController::class, 'addTcode'])->name('add.tcode');

    Route::post('/add-role', [App\Http\Controllers\Role\RoleController::class, 'addRole'])->name('add.role');

    Route::post('/update-role', [App\Http\Controllers\Role\RoleController::class, 'updateRole'])->name('update.role');

    Route::post('/add-employee', [App\Http\Controllers\Employee\EmployeeController::class, 'addEmployee'])->name('add.employee');

    Route::post('/create-employee', [App\Http\Controllers\Employee\EmployeeController::class, 'createEmployee'])->name('create.employee');

    Route::post('/edit-employee', [App\Http\Controllers\Employee\EmployeeController::class, 'editEmployee'])->name('edit.employee');

    /** Sap Request Reads & Writes */
    Route::get('/tcodes-for-user', [App\Http\Controllers\Request\SapController::class, 'modulesAndTCodes'])->name('tcodes.for.user');
    Route::post('/add-sap-request', [App\Http\Controllers\Request\SapController::class, 'saveRequest'])->name('save.sap.request');
    Route::get('/fetch-request', [App\Http\Controllers\Request\SapController::class, 'fetchSelfRequest'])->name('fetch.self.request');

});