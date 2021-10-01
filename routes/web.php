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
use App\Http\Controllers\SO\SoController;
use App\Http\Controllers\SO\SalesOrgController;
use App\Http\Controllers\SO\DistributionController;
use App\Http\Controllers\SO\DivisionController;
use App\Http\Controllers\PO\PoController;
use App\Http\Controllers\PO\PoReleaseController;
use App\Http\Controllers\PO\PoGroupController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Moderator\ModeratorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BusinessAreaController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\Mail\MailTemplateController;
use App\Http\Controllers\Mail\VariableController;
use App\Http\Controllers\Assets\AssetsController;

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
/** Exceptions */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'VisitLogs']], function () {
    /** User Profile */
    Route::get('/employee/profile/{id}', [EmployeeController::class, 'profile'])->name('employee.profile');

});
/** All Views */
Route::group(['prefix' => 'app', 'middleware' => ['auth', 'permissions', 'VisitLogs']], function () {

    Route::get('/change-password', [UserController::class, 'changePasswordPage'])->name('user.change.password');
    Route::get('/users-permission', [UserController::class, 'index'])->name('users.view');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.view');
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.view');
    Route::get('/modules', [PermissionController::class, 'index'])->name('permissions.view');
    Route::get('/app/permissions/list', [PermissionController::class, 'app'])->name('app.permissions');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.view');
    Route::get('/tree', [TreeController::class, 'index'])->name('tree.view');
    Route::get('/sap/request', [SapController::class, 'index'])->name('sap.view');
    Route::get('/team/sap/request', [SapController::class, 'team'])->name('team.sap.view');
    Route::get('/email/request', [EmailController::class, 'index'])->name('email.view');
    Route::get('/module/approval/stages',[ModuleController::class, 'approval_matrix'])->name('approval.matrix');
    /** Critical T codes */
    Route::get('/critical/tcodes/list',[ModuleController::class, 'critical_tcodes'])->name('critical.tcodes.list');

    Route::get('/critical/tcodes',[SapController::class, 'critical_index'])->name('view.criticals');
    /** Moderators */
    Route::get('/moderators',[ModeratorController::class, 'index'])->name('view.moderators');
    /** Departments */
    Route::get('/departments',[DepartmentController::class, 'index'])->name('view.departments');
    /** Designation  */
    Route::get('/designations',[DesignationController::class, 'index'])->name('view.designations');
    /** Company */
    Route::get('/company',[CompanyController::class, 'index'])->name('view.company');
    /** Business Area */
    Route::get('/business/area',[BusinessAreaController::class, 'index'])->name('view.business');
    /** Storage Area */
    Route::get('/storage',[StorageController::class, 'index'])->name('view.storage');
    /** Sales Office */
    Route::get('/sales/office',[SOController::class, 'index'])->name('view.sales.office');
    /** Sales Org */
    Route::get('/sales/organization',[SalesOrgController::class, 'index'])->name('view.sales.org');
    /** Distribution Channel */
    Route::get('/distribution/channel',[DistributionController::class, 'index'])->name('view.distributions');
    /** Distribution Channel */
    Route::get('/divisions',[DivisionController::class, 'index'])->name('view.divisions');
    /** Purhcase Organization */
    Route::get('/purchase/org',[POController::class, 'index'])->name('view.purchase.org');
    /** PO Release */
    Route::get('/po/release',[PoReleaseController::class, 'index'])->name('view.po.release');
    /** PO Group */
    Route::get('/po/group',[PoGroupController::class, 'index'])->name('view.po.group');

    /** Assets */
    Route::get('/assets',[AssetsController::class, 'index'])->name('view.assets');

    /** Employee SAP Access Report */
    Route::get('/sap/report',[EmployeeController::class, 'sap_report'])->name('view.sap.report');

    /** Logs */
    Route::get('/login/logs',[LogsController::class, 'login'])->name('view.login.logs');
    Route::get('/visits/logs',[LogsController::class, 'visits'])->name('view.page.visits');
    Route::get('/activities/logs',[LogsController::class, 'activities'])->name('view.user.activity.logs');

    /** Mail Templates */
    Route::get('/mail/templates',[MailTemplateController::class, 'index'])->name('view.mail.templates');

});

/** Ajax Read Calls */
Route::group(['prefix' => 'listings'], function () {
    
    Route::get('/fetch/users-list/{token}', [UserController::class, 'fetchUsers'])->name('users.list');
    Route::get('/fetch/menu/list', [MenuController::class, 'fetchMenus'])->name('get.menu.list');
    Route::get('/fetch/role/list', [RoleController::class, 'fetchRoles'])->name('get.roles.list');
    Route::get('/fetch/role/permissions', [RoleController::class, 'fetchRolePermissions'])->name('show.role.permissions');
    Route::get('/fetch/permissions-list', [PermissionController::class, 'fetchPermissions'])->name('show.permissions');
    Route::get('/fetch/employee-list/{token}', [EmployeeController::class, 'fetchEmployees'])->name('fetch.employees');
    Route::get('/fetch/states/list', [AreaController::class, 'fetchStates'])->name('fetch.states');
    Route::get('/fetch/districts/list', [AreaController::class, 'fetchDistricts'])->name('fetch.districts');
    Route::get('/fetch/companies/list', [EmployeeController::class, 'fetchCompanies'])->name('fetch.companies');
    Route::get('/fetch/departments/list', [EmployeeController::class, 'fetchDepartments'])->name('fetch.departments');
    Route::get('/fetch/employee/sap/report', [EmployeeController::class, 'fetchEmployeeSAPReport'])->name('show.sap.report');
    Route::get('/fetch/roles/list', [EmployeeController::class, 'fetchRoles'])->name('fetch.roles');
    Route::get('/fetch/report-to/list', [EmployeeController::class, 'fetchReportTos'])->name('fetch.report_tos');
    Route::get('/fetch/plant/list', [PlantController::class, 'getPlants'])->name('get.plants');
    Route::get('/fetch/storage/list', [StorageController::class, 'getStorages'])->name('get.storages');
    Route::get('/fetch/sales_office/list', [SoController::class, 'getSalesOffice'])->name('get.sales_office');
    Route::get('/fetch/request', [SapController::class, 'fetchSelfRequest'])->name('fetch.self.request');
    Route::get('/fetch/critical/request', [SapController::class, 'fetchCriticalSelfRequest'])->name('fetch.self.critical.request');
    Route::get('/fetch/moderator', [ModeratorController::class, 'fetchModerators'])->name('fetch.moderators');
    Route::get('/fetch/team-request', [SapController::class, 'fetchTeamRequest'])->name('fetch.team.request');
    Route::get('/fetch/stages', [ModuleController::class, 'fetchStages'])->name('fetch.stages');
    /** App Permissions */
    Route::get('/fetch-app-permissions-list', [PermissionController::class, 'appPermissions'])->name('show.app.permissions');
    Route::get('/fetch-module-tcodes', [PermissionController::class, 'fetchModuleTCodes'])->name('fetch.module.tcodes');
    /** Module Approval Stages List */
    Route::get('/fetch-module-approval-stages', [ModuleController::class,'fetchModuleApprovalStages'])->name('module.approval.stages');
    /** Fetch Critical Tcode list */
    Route::get('/fetch-critical-tcode-list', [ModuleController::class,'criticalTCodes'])->name('fetch.critical.tcodes');
    Route::get('/fetch-departments', [DepartmentController::class, 'get'])->name('get.departments');
    Route::get('/fetch-designations', [DesignationController::class, 'get'])->name('get.designations');
    Route::get('/fetch-companies', [CompanyController::class, 'get'])->name('get.companies');
    Route::get('/fetch-companies', [CompanyController::class, 'get'])->name('get.company');
    Route::get('/fetch-business-areas', [BusinessAreaController::class, 'get'])->name('get.business.area');
    Route::get('/fetch-storage', [StorageController::class, 'get'])->name('get.storage');
    Route::get('/fetch-sales-office', [SOController::class, 'get'])->name('get.sales.office');
    Route::get('/fetch-sales-org', [SalesOrgController::class, 'get'])->name('get.sales.org');
    Route::get('/fetch-distributions', [DistributionController::class, 'get'])->name('get.distributions');
    Route::get('/fetch-division', [DivisionController::class, 'get'])->name('get.division');
    Route::get('/fetch-purchase-org', [POController::class, 'get'])->name('get.purchase.org');
    Route::get('/fetch-po-release', [POReleaseController::class, 'get'])->name('get.po.release');
    Route::get('/fetch-po-group', [PoGroupController::class, 'get'])->name('get.po.group');
    /** PO Group Add and Update */
    Route::get('/fetch-assets', [AssetsController::class, 'get'])->name('get.assets');
    Route::get('/fetch/tcodes/for-user', [SapController::class, 'modulesAndTCodes'])->name('tcodes.for.user');
    
    /** Logs */
    Route::get('/fetch-login-logs', [LogsController::class, 'fetch_login_logs'])->name('get.login.logs');
    Route::get('/fetch-visit-logs', [LogsController::class, 'fetch_visit_logs'])->name('get.visit.logs');
    Route::get('/fetch-activity-logs', [LogsController::class, 'fetch_activity_logs'])->name('get.activity.logs');

    /** Mail Templates */
    Route::get('/fetch-mail-templates', [MailTemplateController::class, 'fetchMailTemplates'])->name('get.mail.templates');

});

/** Ajax Create/Update/Delete calls with logs */
Route::group(['prefix' => 'activity', 'middleware' => ['auth', 'Logs']], function () {

    Route::get('/trash/permission', [PermissionController::class, 'trashTcode'])->name('trash.tcode');
    Route::get('/temp', [SoController::class, 'set_so_id'])->name('x.temp');
    Route::post('add/menu', [MenuController::class, 'addMenu'])->name('add.menu');
    Route::post('menu/reorder', [MenuController::class, 'reorderMenu'])->name('menu.reorder');
    Route::post('update/user/permission', [UserController::class, 'updatePermissions'])->name('update.user.permissions');
    Route::post('update/user/role', [UserController::class, 'updateRole'])->name('update.user.roles');
    Route::post('update/user/menus', [UserController::class, 'updateMenus'])->name('update.user.menus');
    Route::post('/update/menu', [MenuController::class, 'updateMenu'])->name('update.menu');
    Route::post('/update/permission', [PermissionController::class, 'updatePermission'])->name('update.permission');
    Route::post('/add/permission', [PermissionController::class, 'addPermission'])->name('add.permission');
    Route::post('/add/tcode', [PermissionController::class, 'addTcode'])->name('add.tcode');
    Route::post('/add/role', [RoleController::class, 'addRole'])->name('add.role');
    Route::post('/update/role', [RoleController::class, 'updateRole'])->name('update.role');
    Route::post('/add/employee', [EmployeeController::class, 'addEmployee'])->name('add.employee');
    Route::post('/add/moderator', [ModeratorController::class, 'add_moderator'])->name('add.moderator');
    Route::post('/create/employee', [EmployeeController::class, 'createEmployee'])->name('create.employee');
    Route::post('/edit/employee', [EmployeeController::class, 'editEmployee'])->name('edit.employee');
    /** Sap Request Reads & Writes */
    
    Route::post('/add/sap/request', [SapController::class, 'saveRequest'])->name('save.sap.request');   
    Route::get('/review/sap/request', [SapController::class, 'reviewRequest'])->name('review.sap.request');
    Route::post('/edit/moderator', [ModeratorController::class, 'updateModerator'])->name('edit.moderator');
    //Route::get('/add-dx-tcode', [PermissionController::class, 'dxStoreTcode'])->name('tcode.store');
    Route::post('/update/dx/tcode', [PermissionController::class, 'dxUpdateTcode'])->name('tcode.update');
    /** Approval section for all  */
    Route::post('/approve/sap/request', [SapController::class, 'approve'])->name('approve.sap.request');  
    /* change.module.approval.stage */
    Route::post('/change/module/approval/stages', [ModuleController::class,'changeModuleApprovalStages'])->name('change.module.approval.stage');
    /* Role wise standard tcode access */
    Route::get('/role/wise/standard/tcode/access',[RoleController::class, 'roleTcodeAccess'])->name('role.tcode.access');
    /** Role wise tcode update / add */
    Route::post('/submit/role/wise/standard/tcode/access',[RoleController::class, 'submitRoleTcodeAccess'])->name('submit.selected.tcodes');
    /** Exixisting tcode role wise access */
    Route::get('/current/tcodes',[RoleController::class, 'getCurrentTcodes'])->name('get.current.tcodes');
    /** Departments Add and Update */
    Route::post('/add/department', [DepartmentController::class, 'create'])->name('add.department');
    Route::post('/update/department', [DepartmentController::class, 'update'])->name('update.department');
    /** Designation Add and Update */
    Route::post('/add/designation', [DesignationController::class, 'create'])->name('add.designation');
    Route::post('/update/designation', [DesignationController::class, 'update'])->name('update.designation');
    /** Company Add and Update */  
    Route::post('/add/company', [CompanyController::class, 'create'])->name('add.company');
    Route::post('/update/company', [CompanyController::class, 'update'])->name('update.company');
    /** Business Area Add and Update */  
    Route::post('/add/business/area', [BusinessAreaController::class, 'create'])->name('add.business.area');
    Route::post('/update/business/area', [BusinessAreaController::class, 'update'])->name('update.business.area');
    /** Storage Add and Update */
    Route::post('/add/storage', [StorageController::class, 'create'])->name('add.storage');
    Route::post('/update/storage', [StorageController::class, 'update'])->name('update.storage');
    /** Sales office Add and Update */ 
    Route::post('/add/sales/office', [SOController::class, 'create'])->name('add.sales.office');
    Route::post('/update/sales/office', [SOController::class, 'update'])->name('update.sales.office');
    /** Sales Org Add and Update */
    Route::post('/add/sales/org', [SalesOrgController::class, 'create'])->name('add.sales.org');
    Route::post('/update/sales/org', [SalesOrgController::class, 'update'])->name('update.sales.org');
    /** Distribution Channel Add and Update */
    Route::post('/add/distributions', [DistributionController::class, 'create'])->name('add.distributions');
    Route::post('/update/distributions', [DistributionController::class, 'update'])->name('update.distributions');
    /** Division Add and Update */
    Route::post('/add/division', [DivisionController::class, 'create'])->name('add.division');
    Route::post('/update/division', [DivisionController::class, 'update'])->name('update.division');
    /** Purhcase Org Add and Update */
    Route::post('/add/purchase/org', [POController::class, 'create'])->name('add.purchase.org');
    Route::post('/update/purchase/org', [POController::class, 'update'])->name('update.purchase.org');
    /** PO Release Add and Update */
    Route::post('/add/po/release', [POReleaseController::class, 'create'])->name('add.po.release');
    Route::post('/update/po/release', [POReleaseController::class, 'update'])->name('update.po.release');
    /** PO Group Add and Update */
    Route::post('/add/po/group', [PoGroupController::class, 'create'])->name('add.po.group');
    Route::post('/update/po/group', [PoGroupController::class, 'update'])->name('update.po.group');
    /** Assets Add and Update */
    Route::post('/add/asset', [AssetsController::class, 'create'])->name('add.asset');
    Route::post('/update/asset', [AssetsController::class, 'update'])->name('update.asset');

    /** Create Duplicate Role */
    Route::post('/create/duplicate/role', [RoleController::class,'createDuplicateRole'])->name('create.duplicate.role');

    /** Mail Templates Add & Update */
    Route::post('/add/mail/template', [MailTemplateController::class, 'create'])->name('create.mail.template');
    Route::post('/update/mail/template', [MailTemplateController::class, 'update'])->name('update.mail.template');
    Route::get('/generateFields', [MailTemplateController::class,'generateFields'])->name('generateFields');
    
    /** Dashboard Analytics */
    Route::get('/approval/analytics',[App\Http\Controllers\HomeController::class, 'approvalTimeAnalytics'])->name('approval.analytics');
    Route::get('/approval/counts',[App\Http\Controllers\HomeController::class, 'approvalCounts'])->name('load.approval.counts');
    Route::get('/log/status',[App\Http\Controllers\HomeController::class, 'logStatus'])->name('log.status');
    Route::get('/req/count',[App\Http\Controllers\HomeController::class, 'requestCounts'])->name('load.req.counts');

    /** User password change */
    Route::post('/user/password/change',[UserController::class, 'changePassword'])->name('change.password');

    /** Import Files */
    Route::post('/import/users', [UserController::class, 'importUsers'])->name('import.users');

});

/** Migration Routes */
Route::get('/migrate-tcodes', [App\Http\Controllers\MigrationController::class, 'tcodeMigrate'])->name('migrate.tcodes');
