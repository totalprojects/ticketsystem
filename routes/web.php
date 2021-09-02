<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Request\SapController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\Area\AreaController;
use App\Http\Controllers\Plant\PlantController;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Controllers\SO\SOController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Moderator\ModeratorController;


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
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'permissions', 'Logs']], function () {

    Route::get('/change-password', [UserController::class, 'changePasswordPage'])->name('user.change.password');

    Route::get('/users', [UserController::class, 'index'])->name('users.view');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.view');

    Route::get('/menus', [MenuController::class, 'index'])->name('menus.view');

    Route::get('/modules', [PermissionController::class, 'index'])->name('permissions.view');

    Route::get('/app-permissions-list', [PermissionController::class, 'app'])->name('app.permissions');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.view');

    Route::get('/tree', [TreeController::class, 'index'])->name('tree.view');

    Route::get('/sap-request', [SapController::class, 'index'])->name('sap.view');

    Route::get('/team-sap-request', [SapController::class, 'team'])->name('team.sap.view');

    Route::get('/email-request', [EmailController::class, 'index'])->name('email.view');

    Route::get('/module-approval-stages',[ModuleController::class, 'approval_matrix'])->name('approval.matrix');

    Route::get('/critical-tcodes-list',[ModuleController::class, 'critical_tcodes'])->name('critical.tcodes.list');

    Route::get('/moderators',[ModeratorController::class, 'index'])->name('view.moderators');
});

/** Ajax Calls */
Route::group(['prefix' => 'ajax'], function () {
    /* Reads */
    Route::get('/users-list/{token}', [UserController::class, 'fetchUsers'])->name('users.list');

    Route::get('menu/list', [MenuController::class, 'fetchMenus'])->name('get.menu.list');

    Route::get('role/list', [RoleController::class, 'fetchRoles'])->name('get.roles.list');

    Route::get('role/permissions', [RoleController::class, 'fetchRolePermissions'])->name('show.role.permissions');

    Route::get('/permissions-list', [PermissionController::class, 'fetchPermissions'])->name('show.permissions');

    Route::get('/employee-list/{token}', [EmployeeController::class, 'fetchEmployees'])->name('fetch.employees');

    Route::get('/states/list', [AreaController::class, 'fetchStates'])->name('fetch.states');

    Route::get('/districts/list', [AreaController::class, 'fetchDistricts'])->name('fetch.districts');

    Route::get('/companies/list', [EmployeeController::class, 'fetchCompanies'])->name('fetch.companies');

    Route::get('/departments/list', [EmployeeController::class, 'fetchDepartments'])->name('fetch.departments');

    Route::get('/roles/list', [EmployeeController::class, 'fetchRoles'])->name('fetch.roles');

    Route::get('/report-to/list', [EmployeeController::class, 'fetchReportTos'])->name('fetch.report_tos');

    Route::get('/plant/list', [PlantController::class, 'getPlants'])->name('get.plants');

    Route::get('/storage/list', [StorageController::class, 'getStorages'])->name('get.storages');

    Route::get('/sales_office/list', [SoController::class, 'getSalesOffice'])->name('get.sales_office');

});

/** Ajax calls with logs */
Route::group(['prefix' => 'ajax', 'middleware' => ['auth']], function () {

    Route::get('/trash-permission', [PermissionController::class, 'trashTcode'])->name('trash.tcode');

    Route::get('/temp', [SoController::class, 'set_so_id'])->name('x.temp');

    /* HTTP CUD */
    Route::post('menu/add', [MenuController::class, 'addMenu'])->name('add.menu');

    Route::post('menu/reorder', [MenuController::class, 'reorderMenu'])->name('menu.reorder');

    Route::post('update/user/permission', [UserController::class, 'updatePermissions'])->name('update.user.permissions');

    Route::post('update/user/role', [UserController::class, 'updateRole'])->name('update.user.roles');

    Route::post('update/user/menus', [UserController::class, 'updateMenus'])->name('update.user.menus');

    Route::post('/update-menu', [MenuController::class, 'updateMenu'])->name('update.menu');

    Route::post('/update-permission', [PermissionController::class, 'updatePermission'])->name('update.permission');

    Route::post('/add-permission', [PermissionController::class, 'addPermission'])->name('add.permission');

    Route::post('/add-tcode', [PermissionController::class, 'addTcode'])->name('add.tcode');

    Route::post('/add-role', [RoleController::class, 'addRole'])->name('add.role');

    Route::post('/update-role', [RoleController::class, 'updateRole'])->name('update.role');

    Route::post('/add-employee', [EmployeeController::class, 'addEmployee'])->name('add.employee');

    Route::post('/add-moderator', [ModeratorController::class, 'add_moderator'])->name('add.moderator');

    Route::post('/create-employee', [EmployeeController::class, 'createEmployee'])->name('create.employee');

    Route::post('/edit-employee', [EmployeeController::class, 'editEmployee'])->name('edit.employee');

    /** Sap Request Reads & Writes */
    Route::get('/tcodes-for-user', [SapController::class, 'modulesAndTCodes'])->name('tcodes.for.user');
    
    Route::post('/add-sap-request', [SapController::class, 'saveRequest'])->name('save.sap.request');
    
    Route::get('/review-sap-request', [SapController::class, 'reviewRequest'])->name('review.sap.request');
    
    Route::get('/fetch-request', [SapController::class, 'fetchSelfRequest'])->name('fetch.self.request');
    Route::get('/fetch-moderator', [ModeratorController::class, 'fetchModerators'])->name('fetch.moderators');
    Route::post('/edit-moderator', [ModeratorController::class, 'updateModerator'])->name('edit.moderator');
    Route::get('/fetch-team-request', [SapController::class, 'fetchTeamRequest'])->name('fetch.team.request');

    Route::get('/fetch-module-tcodes', [PermissionController::class, 'fetchModuleTCodes'])->name('fetch.module.tcodes');

    //Route::get('/add-dx-tcode', [PermissionController::class, 'dxStoreTcode'])->name('tcode.store');
    Route::post('/update-dx-tcode', [PermissionController::class, 'dxUpdateTcode'])->name('tcode.update');

    /** App Permissions */
    Route::get('/fetch-app-permissions-list', [PermissionController::class, 'appPermissions'])->name('show.app.permissions');

    /** Approval section for all  */
    Route::post('/approve-sap-request-stage-1', [SapController::class, 'approveByRM'])->name('approve.sap.request.by.rm');

    /** Module Approval Stages List */
    Route::get('/fetch-module-approval-stages', [ModuleController::class,'fetchModuleApprovalStages'])->name('module.approval.stages');
   
    /** Fetch Critical Tcode list */
    Route::get('/fetch-critical-tcode-list', [ModuleController::class,'criticalTCodes'])->name('fetch.critical.tcodes');
    
    /* change.module.approval.stage */
    Route::post('/change-module-approval-stages', [ModuleController::class,'changeModuleApprovalStages'])->name('change.module.approval.stage');

    /* Role wise standard tcode access */
    Route::get('/role-wise-standard-tcode-access',[RoleController::class, 'roleTcodeAccess'])->name('role.tcode.access');
    /** Role wise tcode update / add */
    Route::post('/submit-role-wise-standard-tcode-access',[RoleController::class, 'submitRoleTcodeAccess'])->name('submit.selected.tcodes');

    /** Exixisting tcode role wise access */
    Route::get('/current-tcodes',[RoleController::class, 'getCurrentTcodes'])->name('get.current.tcodes');
});

/** Migration Routes */
Route::get('/migrate-tcodes', [App\Http\Controllers\MigrationController::class, 'tcodeMigrate'])->name('migrate.tcodes');
