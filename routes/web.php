<?php

use App\Http\Controllers\BoodschappenController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FactureerlijstController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeFactController;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\SiteController;
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

Auth::routes();
//UserController Routes
Route::get('/login/check', [App\Http\Controllers\UserController::class, 'checkLogin'])->name('checkLogin');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');
    Route::get('/gebruikers/selecteren/{id}', [\App\Http\Controllers\UserController::class, 'selectUser'])->name('selectUser');

    //HomeController Routes
    Route::get('/', [App\Http\Controllers\HomeController::class, 'kalenderTest'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'kalenderTest'])->name('home');
//    Route::get('/home-test', [App\Http\Controllers\HomeController::class, 'kalenderTest'])->name('home-test');
    Route::get('/csv', [App\Http\Controllers\HomeController::class, 'csv'])->name('csv');

    // Keysoftware Klanten
    Route::prefix('/keysoftware')->name('keysoftware.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'index'])->name('index');
        Route::get('/makelaar-bekijken/{id}', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'show'])->name('show');
        Route::get('/genereer-api-token', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'generateApiToken'])->name('generate-api-token');

        Route::get('/makelaar-bekijken/{id}/pagina-laden/{view}', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'loadNewPage'])->name('loadNewPage');
        Route::get('/makelaar-bekijken/{id}/tijdlijn-aanpassen/{year}', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'changePerformanceTime'])->name('changePerformanceTime');
        Route::get('/makelaars-zoeken', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'search'])->name('search');


        Route::get('/makelaar/verwijderen/{id}', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'delete'])->name('delete.makelaar');
        Route::post('/makelaar/aanmaken', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'create'])->name('create.makelaar');
        Route::post('/makelaar/bijwerken/{id}', [\App\Http\Controllers\KeysoftwareCustomerController::class, 'edit'])->name('edit.makelaar');

        // Product price update
        Route::post('/product-prijzen/bijwerken', [\App\Http\Controllers\KeysoftwarePlanviewerProductsController::class, 'update'])->name('update.products');
    });

    //CallController Routes
    Route::prefix('/home')->name('home.')->group(function () {
        Route::post('/belnotities/aanmaken', [\App\Http\Controllers\CallController::class, 'create']);
        Route::get('/belnotities/verwijderen/{id}', [\App\Http\Controllers\CallController::class, 'delete']);
        Route::get('/belnotities/autofill/{id}', [\App\Http\Controllers\CallController::class, 'autofill']);
        Route::get('/uren/opslaan/{projId}/{fromTime}/{toTime}', [\App\Http\Controllers\WorkOrderController::class, 'create']);
        Route::get('/uren/custom-opslaan', [\App\Http\Controllers\WorkOrderController::class, 'createCustom']);
        Route::get('/uren/laden', [\App\Http\Controllers\WorkOrderController::class, 'load']);
        Route::get('/uren/datum-veranderen/{date}/{position}', [\App\Http\Controllers\WorkOrderController::class, 'changeDate']);
        Route::get('/uren/bekijken/{id}', [\App\Http\Controllers\WorkOrderController::class, 'viewWorkOrder']);
        Route::get('/uren/wijzigen/{id}', [\App\Http\Controllers\WorkOrderController::class, 'editWorkOrder']);
        Route::get('/uren/verwijderen/{id}', [\App\Http\Controllers\WorkOrderController::class, 'deleteWorkOrder']);
        Route::post('/uren/eenmaligebedragen/', [\App\Http\Controllers\WorkOrderController::class, 'eenmaligeBedrag'])->name('eenmalig');

        // Todos
        Route::get('/todos/haal-categorieen', [\App\Http\Controllers\TodoController::class, 'getProjectCategories']);
        Route::get('/todos/filter-get-projects', [\App\Http\Controllers\TodoController::class, 'getFilterProjects']);
        Route::get('/todos/filter', [\App\Http\Controllers\TodoController::class, 'filter']);
        Route::post('/todos/opslaan', [\App\Http\Controllers\TodoController::class, 'createTodo'])->name('todos.save');
        Route::post('/todos/wijzigen/{todoId}', [\App\Http\Controllers\TodoController::class, 'editTodo'])->name('todos.edit');
        Route::get('/todos/bekijken/{todoId}', [\App\Http\Controllers\TodoController::class, 'getTodo'])->name('todos.get');
        Route::get('/todos/taak-afronden/{todoId}', [\App\Http\Controllers\TodoController::class, 'finishTodo'])->name('todos.finish');
    });

    Route::get('/uren/overzicht', [\App\Http\Controllers\WorkOrderController::class, 'showSummary']);
    Route::get('/uren/overzicht-laden', [\App\Http\Controllers\WorkOrderController::class, 'reloadSummary']);
    Route::get('/uren/overzicht/tijdlijn-aanpassen/{month}/{year}', [\App\Http\Controllers\WorkOrderController::class, 'changeTimeline']);

    Route::prefix('/klanten')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/aanmaken', [CustomerController::class, 'create'])->name('create');
        Route::get('/zoeken', [CustomerController::class, 'search'])->name('search');
        Route::get('/bekijken/{id}', [CustomerController::class, 'show'])->name('show');
        Route::get('/bekijken/{id}/contactpersonen/verwijderen/{cpId}', [CustomerController::class, 'deleteContact'])->name('deleteContact');
        Route::get('/bekijken/{id}/adressen/verwijderen/{adrId}', [CustomerController::class, 'deleteAddress'])->name('deleteAddress');
        Route::post('/aanpassen/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::get('/bekijken/{id}/pagina-laden/{view}', [CustomerController::class, 'loadNewPage'])->name('loadNewPage');
        Route::get('/verwijderen/{id}', [CustomerController::class, 'deleteCustomer'])->name('deleteCustomer');
    });

    Route::prefix('/projecten')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/laatst-aangemaakt', [ProjectController::class, 'getLast'])->name('getLast');
        Route::post('/aanmaken', [ProjectController::class, 'create'])->name('create');
        Route::get('/bekijken/{id}', [ProjectController::class, 'show'])->name('show');
        Route::get('/bekijken/{id}/pagina-laden/{view}', [ProjectController::class, 'loadNewPage'])->name('loadNewPage');
        Route::get('/verwijderen/{id}', [ProjectController::class, 'deleteCustomer'])->name('deleteCustomer');
        Route::get('/bekijken/{id}/taak-afronden/{todoId}', [ProjectController::class, 'finishTask'])->name('finishTask');
        Route::get('/bekijken/{id}/taak-openen/{todoId}', [ProjectController::class, 'openTask'])->name('openTask');
        Route::post('/bekijken/{id}/taak-aanmaken', [ProjectController::class, 'createTask'])->name('createTask');
        Route::post('/bekijken/{id}/taak-aanpassen/{todoId}', [ProjectController::class, 'editTask'])->name('editTask');
        Route::get('/bekijken/{id}/taak-bekijken/{todoId}', [ProjectController::class, 'showTask'])->name('showTask');
        Route::get('/bekijken/{id}/tijdlijn-aanpassen/{month}/{year}', [ProjectController::class, 'changeTimeline'])->name('changeTimeline');
        Route::get('/zoeken', [ProjectController::class, 'search'])->name('search');
        Route::post('/aanpassen/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::get('/bekijken/{id}/status-veranderen/{status}', [ProjectController::class, 'editStatus'])->name('editStatus');
        Route::get('/jaarfacturen/overzicht/{year}', [ProjectController::class, 'yearlyInvoices'])->name('yearly-invoices');
        Route::post('/jaarfacturen/overzicht', [ProjectController::class, 'saveYearlyInvoices'])->name('save-yearly-invoices');
    });

    Route::prefix('/gebruikers')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/aanmaken', [UserController::class, 'create'])->name('create');
        Route::get('/zoeken', [UserController::class, 'search'])->name('search');
        Route::get('/bekijken/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/bekijken/{id}/pagina-laden/{view}', [UserController::class, 'loadView'])->name('loadView');
        Route::get('/bekijken/{id}/tijdlijn-aanpassen/{month}/{year}', [UserController::class, 'changePerformanceTime'])->name('changePerformanceTime');
        Route::get('/verwijderen/{id}', [UserController::class, 'delete'])->name('delete');
        Route::post('/aanpassen/{id}', [UserController::class, 'edit'])->name('edit');
    });

    Route::prefix('/games')->name('games.')->group(function () {
        Route::get('/', [HomeController::class, 'games'])->name('games');
        Route::get('/spelen/dino-run', [HomeController::class, 'dinoRun'])->name('dinoRun');
        Route::get('/spelen/monkey-island', [HomeController::class, 'monkeyIsland'])->name('monkeyIsland');
        Route::get('/spelen/spider-solitaire', [HomeController::class, 'spiderSolitaire'])->name('spiderSolitaire');
        Route::get('/spelen/gold-miner', [HomeController::class, 'goldMiner'])->name('goldMiner');
    });

    Route::post('/push', [PushController::class, 'push']);

    Route::get('/schoonmaak-rooster', [HomeController::class, 'cleaningSchema'])->name('cleaningSchema');

    Route::post('/eenmalig-bedrag', [WorkOrderController::class, 'eenmaligeBedrag'])->name('eenmalig-bedrag');


    // Wiki routes.
    Route::get('/wiki', [HomeController::class, 'wiki'])->name('wiki');
    Route::post('/wiki', [WikiController::class, 'create'])->name('wiki.submit');
    Route::get('/wiki-post/{id}', [WikiController::class, 'show'])->name('wiki.show');
    Route::post('/comment', [WikiController::class, 'submitComment'])->name('wiki.comment');
    Route::post('/delete-comment', [WikiController::class, 'deleteComment'])->name('delete.comment');
    Route::post('/delete-post', [WikiController::class, 'deletePostAndComments'])->name('delete.post');
    Route::get('/edit-post/{id}', [HomeController::class, 'editWiki'])->name('wiki.edit');
    Route::post('/edit-post', [WikiController::class, 'editPost'])->name('wiki.editpost');
    Route::get('/edit-comment/{id}', [HomeController::class, 'editWikiComment'])->name('wiki.edit-comment');
    Route::post('/edit-comment', [WikiController::class, 'editComment'])->name('edit.comment');


    // Kalender routes.
    Route::get('/kalender', [HomeController::class, 'kalender'])->name('kalender');
    Route::post('/kalender', [EventsController::class, 'create'])->name('event.submit');
    Route::post('/kalender-edit', [EventsController::class, 'edit'])->name('event.edit');
    Route::post('/kalender-delete', [EventsController::class, 'delete'])->name('event.delete');
    Route::post('/kalender-workorder', [EventsController::class, 'saveWorkorder'])->name('event.workorder');
    Route::get('/kalender-load/{date}', [EventsController::class, 'reload']);
    Route::get('/kalender-filter/{user}', [EventsController::class, 'filter']);
    Route::get('/kalender-today', [EventsController::class, 'today']);
    Route::get('/kalender-add/{date}', [EventsController::class, 'addWeek']);
    Route::get('/kalender-sub/{date}', [EventsController::class, 'subtractWeek']);


    // Boodschappenlijst routes.
    Route::get('/boodschappenlijst', [HomeController::class, 'boodschappenLijst'])->name('boodschappenlijst');
    Route::post('/boodschappenlijst', [BoodschappenController::class, 'create'])->name('lijst.submit');
    Route::get('/boodschappenlijst/{id}', [BoodschappenController::class, 'show'])->name('lijst.show');
    Route::get('/bewerk-boodschappenlijst/{id}', [BoodschappenController::class, 'edit'])->name('lijst.edit');
    Route::post('/bewerk-boodschappenlijst', [BoodschappenController::class, 'editLijstje'])->name('lijst.edit.submit');
    Route::post('/verwijder-boodschappenlijst', [BoodschappenController::class, 'delete'])->name('delete.lijst');


    // To do routes.
    Route::get('/todo', [HomeController::class, 'todo'])->name('todo');
    Route::post('/todo-create', [TodoController::class, 'createTodo']);
    Route::post('/todo-edit', [TodoController::class, 'editTodo']);
    Route::post('/todo-delete', [TodoController::class, 'deleteTodo']);
    Route::post('/todo-finish', [TodoController::class, 'finishTodo']);

    Route::get('/todo-board/{id}', [TodoController::class, 'showBoard'])->name('todo.show');
    Route::post('/todo-create-board', [TodoController::class, 'createBoard']);
    Route::post('/todo-edit-board', [TodoController::class, 'editBoard']);
    Route::post('/todo-delete-board', [TodoController::class, 'deleteBoard']);
    Route::get('/todo-reload-boards', [TodoController::class, 'reloadBoards']);
    Route::get('/todo-reload-boards-list', [TodoController::class, 'reloadBoardsList']);
    Route::get('/todo-status-boards/{status}', [TodoController::class, 'reloadBoardsStatus']);

    Route::post('/todo-create-list', [TodoController::class, 'createList']);
    Route::post('/todo-edit-list', [TodoController::class, 'editList']);
    Route::post('/todo-delete-list', [TodoController::class, 'deleteList']);
    Route::get('/todo-reload-lists/{id}', [TodoController::class, 'reloadLists']);
    Route::get('/todo-finished-lists/{id}', [TodoController::class, 'loadfinished']);


    // Notes routes.
    Route::get('/note-reload', [NotesController::class, 'reload']);
    Route::post('/note-create', [NotesController::class, 'create']);
    Route::post('/note-delete', [NotesController::class, 'delete']);
    Route::post('/note-edit', [NotesController::class, 'edit']);
    Route::get('/note-view/{id}', [NotesController::class, 'show']);


    // WeFact routes.
    Route::get('/wefact', [WeFactController::class, 'index'])->name('wefact');
    Route::get('/reload-facturen', [WeFactController::class, 'reloadFacturen']);
    Route::post('/upload-factuur', [WeFactController::class, 'weFactUpload']);
    Route::post('/export-factuur', [WeFactController::class, 'weFactExport'])->name('wefact-export');
    Route::post('/delete-factuur', [WeFactController::class, 'deleteFile']);

    Route::get('/wefact/import-customers-and-projects', [WeFactController::class, 'importCustomersAndProjects'])->name('wefact.import-customers-and-projects');



    // Factureerlijst routes
    Route::get('/factureerlijst', [FactureerlijstController::class, 'index']);
    Route::get('/reload-factureerlijst', [FactureerlijstController::class, 'reload']);
    Route::get('/filter-factureerlijst/{month}', [FactureerlijstController::class, 'filter']);
    Route::get('/check-factuur/{id}/{price}/{product}', [FactureerlijstController::class, 'checkFactuur']);
    Route::get('/uncheck-factuur/{id}', [FactureerlijstController::class, 'uncheckFactuur']);


    // Site-Crm Routes
    Route::get('/site-projecten', [SiteController::class, 'index']);
    Route::post('/site-projecten/opslaan', [SiteController::class, 'saveCreate'])->name('siteProjects.save');
    Route::get('/site-projecten/content/{siteId}', [SiteController::class, 'showContent'])->name('siteProjects.content');
    Route::get('/site-projecten/edit-sort/content', [SiteController::class, 'editSort'])->name('siteProjects.edit-sort');
    Route::post('/site-projecten/content-opslaan-text/{siteId}', [SiteController::class, 'saveContentText'])->name('siteProjectsContentText.save');
    Route::post('/site-projecten/content-opslaan-foto/{siteId}', [SiteController::class, 'saveContentFoto'])->name('siteProjectsContentFoto.save');
    Route::get('/site-projecten/haal-projecten', [SiteController::class, 'getProjects'])->name('siteProjects.get-projects');
    Route::get('/site-projecten/haal-projecten-content/{siteId}', [SiteController::class, 'getProjectsContent'])->name('siteProjectsContents.get-projects');
    Route::get('/site-projecten/wijzig-case', [SiteController::class, 'edit']);
    Route::post('/site-projecten/wijzig-case/{caseId}', [SiteController::class, 'saveEdit'])->name('siteProjects.saveEdit');
    Route::get('/site-projecten/deleted-case/{caseId}', [SiteController::class, 'deleteCase'])->name('siteProjects.deleteCase');
    Route::get('/site-projecten/deleted-content/{cid}', [SiteController::class, 'deleteCaseContent'])->name('siteProjects.deleteCaseContent');
    Route::get('/site-projecten/wijzig-case-content', [SiteController::class, 'editContent']);
    Route::post('/site-projecten/wijzig-case-content-opslaan/{cId}', [SiteController::class, 'saveEditContent'])->name('saveEditContent.save');
    Route::get('/overview', [SiteController::class, 'showResulat'])->name('overview.show');

    Route::get('/site-fetch-test', [SiteController::class, 'testFetch'])->name('overview.test-fetch');

    Route::get('/site-fetch-test-content/{siteId}', [SiteController::class, 'testFetchContent'])->name('test-fetch-content');

});

