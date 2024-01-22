<?php

use App\Http\Controllers\ProductionDashboardController;
use App\Http\Controllers\MachineDashboardController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\FinalCheckingController;
use App\Http\Controllers\MaterialStockController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\WarehouseOutController;
use App\Http\Controllers\CheckPelleteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaterialOutController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\WarehouseInController;
use App\Http\Controllers\MaterialInController;
use App\Http\Controllers\ShotblastController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrillingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\GrindingController;
use App\Http\Controllers\PelleteController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UomController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => true
]);

Route::middleware('auth')->group(function () {

    //PROFILE
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/user/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/user/profile/password', [ProfileController::class, 'password'])->name('profile.password.update');

    //NOTIFICATION
    Route::get('/notifications/get', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/productions/purchase-order/review/{id}', [NotificationController::class, "review"])->name('notification.review');
    Route::post('/productions/purchase-order/accept/{id}', [NotificationController::class, "accept"])->name('purchase.accept');
    Route::post('/productions/purchase-order/reject/{id}', [NotificationController::class, "reject"])->name('purchase.reject');
    
    //DASHBOARD
    Route::get('/dashboard/main', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/main/get', [DashboardController::class, 'get'])->name('dashboard.get');

    //MACHINE DASHBOARD
    Route::get('/dashboard/machine', [MachineDashboardController::class, 'index'])->name('machine_dashboard');
    Route::get('/dashboard/machine/get', [MachineDashboardController::class, 'get'])->name('machine_dashboard.get');

    //PRODUCTION DASHBOARD
    Route::get('/dashboard/production', [ProductionDashboardController::class, 'index'])->name('production_dashboard');
    Route::get('/dashboard/production/get', [ProductionDashboardController::class, 'get'])->name('production_dashboard.get');

    //MATERIAL STOCK
    Route::get('/reports/material-stock', [MaterialStockController::class, 'index'])->name('material-stock');
    Route::post('/reports/material-stock/get', [MaterialStockController::class, 'get'])->name('material-stock.get');

    //REPORT
    Route::get('/reports/report', [ReportController::class, 'index'])->name('report');
    Route::post('/reports/report', [ReportController::class, 'index'])->name('report.get');

    //STOCK REPORT
    Route::get('/reports/stock-report', [StockReportController::class, 'index'])->name('stock-report');
    Route::post('/reports/stock-report/get', [StockReportController::class, 'get'])->name('stock-report.get');

    //USER
    Route::get('/users/user/index', [UserController::class, "index"])->name('user.index');
    Route::get('/users/user/create', [UserController::class, "create"])->name('user.create');
    Route::post('/users/user/store', [UserController::class, "store"])->name('user.store');
    Route::get('/users/user/edit/{id}', [UserController::class, "edit"])->name('user.edit');
    Route::post('/users/user/update/{id}', [UserController::class, "update"])->name('user.update');
    Route::get('/users/user/destroy/{id}', [UserController::class, "destroy"])->name('user.destroy');

    //ROLE
    Route::get('/users/role/index', [RoleController::class, "index"])->name('role.index');
    Route::get('/users/role/create', [RoleController::class, "create"])->name('role.create');
    Route::post('/users/role/store', [RoleController::class, "store"])->name('role.store');
    Route::get('/users/role/edit/{id}', [RoleController::class, "edit"])->name('role.edit');
    Route::post('/users/role/update/{id}', [RoleController::class, "update"])->name('role.update');
    Route::get('/users/role/destroy/{id}', [RoleController::class, "destroy"])->name('role.destroy');

    //PRODUCT
    Route::get('/products/product/index', [ProductController::class, "index"])->name('product.index');
    Route::get('/products/product/create', [ProductController::class, "create"])->name('product.create');
    Route::post('/products/product/store', [ProductController::class, "store"])->name('product.store');
    Route::get('/products/product/edit/{id}', [ProductController::class, "edit"])->name('product.edit');
    Route::post('/products/product/update/{id}', [ProductController::class, "update"])->name('product.update');
    Route::get('/products/product/destroy/{id}', [ProductController::class, "destroy"])->name('product.destroy');

    //CATEGORY
    Route::get('/materials/category/index', [CategoryController::class, "index"])->name('category.index');
    Route::get('/materials/category/create', [CategoryController::class, "create"])->name('category.create');
    Route::post('/materials/category/store', [CategoryController::class, "store"])->name('category.store');
    Route::get('/materials/category/edit/{id}', [CategoryController::class, "edit"])->name('category.edit');
    Route::post('/materials/category/update/{id}', [CategoryController::class, "update"])->name('category.update');
    Route::get('/materials/category/destroy/{id}', [CategoryController::class, "destroy"])->name('category.destroy');

    //UOM
    Route::get('/materials/uom/index', [UomController::class, "index"])->name('uom.index');
    Route::get('/materials/uom/create', [UomController::class, "create"])->name('uom.create');
    Route::post('/materials/uom/store', [UomController::class, "store"])->name('uom.store');
    Route::get('/materials/uom/edit/{id}', [UomController::class, "edit"])->name('uom.edit');
    Route::post('/materials/uom/update/{id}', [UomController::class, "update"])->name('uom.update');
    Route::get('/materials/uom/destroy/{id}', [UomController::class, "destroy"])->name('uom.destroy');

    //SUPPLIER
    Route::get('/materials/supplier/index', [SupplierController::class, "index"])->name('supplier.index');
    Route::get('/materials/supplier/create', [SupplierController::class, "create"])->name('supplier.create');
    Route::post('/materials/supplier/store', [SupplierController::class, "store"])->name('supplier.store');
    Route::get('/materials/supplier/edit/{id}', [SupplierController::class, "edit"])->name('supplier.edit');
    Route::post('/materials/supplier/update/{id}', [SupplierController::class, "update"])->name('supplier.update');
    Route::get('/materials/supplier/destroy/{id}', [SupplierController::class, "destroy"])->name('supplier.destroy');

    //MATERIAL
    Route::get('/materials/material/index', [MaterialController::class, "index"])->name('material.index');
    Route::get('/materials/material/create', [MaterialController::class, "create"])->name('material.create');
    Route::post('/materials/material/store', [MaterialController::class, "store"])->name('material.store');
    Route::get('/materials/material/edit/{id}', [MaterialController::class, "edit"])->name('material.edit');
    Route::post('/materials/material/update/{id}', [MaterialController::class, "update"])->name('material.update');
    Route::get('/materials/material/destroy/{id}', [MaterialController::class, "destroy"])->name('material.destroy');

    //MATERIAL IN
    Route::get('/materials/material-in/index', [MaterialInController::class, "index"])->name('material-in.index');
    Route::get('/materials/material-in/create', [MaterialInController::class, "create"])->name('material-in.create');
    Route::post('/materials/material-in/store', [MaterialInController::class, "store"])->name('material-in.store');
    Route::get('/materials/material-in/edit/{id}', [MaterialInController::class, "edit"])->name('material-in.edit');
    Route::post('/materials/material-in/update/{id}', [MaterialInController::class, "update"])->name('material-in.update');
    Route::get('/materials/material-in/destroy/{id}', [MaterialInController::class, "destroy"])->name('material-in.destroy');

    //MATERIAL OUT
    Route::get('/materials/material-out/index', [MaterialOutController::class, "index"])->name('material-out.index');
    Route::get('/materials/material-out/create', [MaterialOutController::class, "create"])->name('material-out.create');
    Route::post('/materials/material-out/store', [MaterialOutController::class, "store"])->name('material-out.store');
    Route::get('/materials/material-out/edit/{id}', [MaterialOutController::class, "edit"])->name('material-out.edit');
    Route::post('/materials/material-out/update/{id}', [MaterialOutController::class, "update"])->name('material-out.update');
    Route::get('/materials/material-out/destroy/{id}', [MaterialOutController::class, "destroy"])->name('material-out.destroy');

    //BATCH
    Route::get('/productions/batch/index', [BatchController::class, "index"])->name('batch.index');
    Route::get('/productions/batch/create', [BatchController::class, "create"])->name('batch.create');
    Route::post('/productions/batch/store', [BatchController::class, "store"])->name('batch.store');
    Route::get('/productions/batch/edit/{id}', [BatchController::class, "edit"])->name('batch.edit');
    Route::post('/productions/batch/update/{id}', [BatchController::class, "update"])->name('batch.update');
    Route::get('/productions/batch/destroy/{id}', [BatchController::class, "destroy"])->name('batch.destroy');

    //MACHINE
    Route::get('/productions/machine/index', [MachineController::class, "index"])->name('machine.index');
    Route::get('/productions/machine/create', [MachineController::class, "create"])->name('machine.create');
    Route::post('/productions/machine/store', [MachineController::class, "store"])->name('machine.store');
    Route::get('/productions/machine/edit/{id}', [MachineController::class, "edit"])->name('machine.edit');
    Route::post('/productions/machine/update/{id}', [MachineController::class, "update"])->name('machine.update');
    Route::get('/productions/machine/limit/{id}', [MachineController::class, "limit"])->name('machine.limit');
    Route::post('/productions/machine/limit_set/{id}', [MachineController::class, "limit_set"])->name('machine.limit_set');
    Route::get('/productions/machine/destroy/{id}', [MachineController::class, "destroy"])->name('machine.destroy');

    //PURCHASE ORDER
    Route::get('/productions/purchase-order/pdf/{id}', [PurchaseOrderController::class, "pdf"])->name('purchase.pdf');
    Route::get('/productions/purchase-order/index', [PurchaseOrderController::class, "index"])->name('purchase.index');
    Route::get('/productions/purchase-order/create', [PurchaseOrderController::class, "create"])->name('purchase.create');
    Route::post('/productions/purchase-order/store', [PurchaseOrderController::class, "store"])->name('purchase.store');
    Route::get('/productions/purchase-order/edit/{id}', [PurchaseOrderController::class, "edit"])->name('purchase.edit');
    Route::post('/productions/purchase-order/update/{id}', [PurchaseOrderController::class, "update"])->name('purchase.update');
    Route::get('/productions/purchase-order/destroy/{id}', [PurchaseOrderController::class, "destroy"])->name('purchase.destroy');

    //PRODUCTION ORDER
    Route::get('/productions/production-order/get', [ProductionOrderController::class, "purchase"])->name('production.get');
    Route::get('/productions/production-order/index', [ProductionOrderController::class, "index"])->name('production.index');
    Route::get('/productions/production-order/create', [ProductionOrderController::class, "create"])->name('production.create');
    Route::post('/productions/production-order/store', [ProductionOrderController::class, "store"])->name('production.store');
    Route::get('/productions/production-order/edit/{id}', [ProductionOrderController::class, "edit"])->name('production.edit');
    Route::post('/productions/production-order/update/{id}', [ProductionOrderController::class, "update"])->name('production.update');
    Route::get('/productions/production-order/destroy/{id}', [ProductionOrderController::class, "destroy"])->name('production.destroy');

    //PRESS
    Route::get('/productions/press/index', [PressController::class, "index"])->name('press.index');
    Route::get('/productions/press/create', [PressController::class, "create"])->name('press.create');
    Route::post('/productions/press/store', [PressController::class, "store"])->name('press.store');
    Route::post('/productions/press/start', [PressController::class, "start"])->name('press.start');
    Route::get('/productions/press/edit/{id}', [PressController::class, "edit"])->name('press.edit');
    Route::post('/productions/press/update/{id}', [PressController::class, "update"])->name('press.update');
    Route::get('/productions/press/destroy/{id}', [PressController::class, "destroy"])->name('press.destroy');

    //SHOTBLAST
    Route::get('/productions/shotblast/index', [ShotblastController::class, "index"])->name('shotblast.index');
    Route::get('/productions/shotblast/create', [ShotblastController::class, "create"])->name('shotblast.create');
    Route::post('/productions/shotblast/store', [ShotblastController::class, "store"])->name('shotblast.store');
    Route::get('/productions/shotblast/edit/{id}', [ShotblastController::class, "edit"])->name('shotblast.edit');
    Route::post('/productions/shotblast/update/{id}', [ShotblastController::class, "update"])->name('shotblast.update');
    Route::get('/productions/shotblast/destroy/{id}', [ShotblastController::class, "destroy"])->name('shotblast.destroy');

    //GRINDING
    Route::get('/productions/in-progress/grinding/index', [GrindingController::class, "index"])->name('grinding.index');
    Route::get('/productions/in-progress/grinding/create', [GrindingController::class, "create"])->name('grinding.create');
    Route::post('/productions/in-progress/grinding/store', [GrindingController::class, "store"])->name('grinding.store');
    Route::get('/productions/in-progress/grinding/edit/{id}', [GrindingController::class, "edit"])->name('grinding.edit');
    Route::post('/productions/in-progress/grinding/update/{id}', [GrindingController::class, "update"])->name('grinding.update');
    Route::get('/productions/in-progress/grinding/destroy/{id}', [GrindingController::class, "destroy"])->name('grinding.destroy');
    Route::get('/productions/in-progress/grinding/pelletes/get', [GrindingController::class, "pelletes"])->name('pelletes.grinding.get');

    //DRILLING
    Route::get('/productions/in-progress/drilling/index', [DrillingController::class, "index"])->name('drilling.index');
    Route::get('/productions/in-progress/drilling/create', [DrillingController::class, "create"])->name('drilling.create');
    Route::post('/productions/in-progress/drilling/store', [DrillingController::class, "store"])->name('drilling.store');
    Route::get('/productions/in-progress/drilling/edit/{id}', [DrillingController::class, "edit"])->name('drilling.edit');
    Route::post('/productions/in-progress/drilling/update/{id}', [DrillingController::class, "update"])->name('drilling.update');
    Route::get('/productions/in-progress/drilling/destroy/{id}', [DrillingController::class, "destroy"])->name('drilling.destroy');
    Route::get('/productions/in-progress/drilling/pelletes/get', [DrillingController::class, "pelletes"])->name('pelletes.drilling.get');

    //FINAL CHECKING
    Route::get('/productions/in-progress/final-checking/index', [FinalCheckingController::class, "index"])->name('final-checking.index');
    Route::get('/productions/in-progress/final-checking/create', [FinalCheckingController::class, "create"])->name('final-checking.create');
    Route::post('/productions/in-progress/final-checking/store', [FinalCheckingController::class, "store"])->name('final-checking.store');
    Route::get('/productions/in-progress/final-checking/edit/{id}', [FinalCheckingController::class, "edit"])->name('final-checking.edit');
    Route::post('/productions/in-progress/final-checking/update/{id}', [FinalCheckingController::class, "update"])->name('final-checking.update');
    Route::get('/productions/in-progress/final-checking/destroy/{id}', [FinalCheckingController::class, "destroy"])->name('final-checking.destroy');
    Route::get('/productions/in-progress/final-checking/pelletes/get', [FinalCheckingController::class, "pelletes"])->name('pelletes.final-checking.get');
    
    //PELLETE
    Route::get('/warehouses/pellete/index', [PelleteController::class, "index"])->name('pellete.index');
    Route::get('/warehouses/pellete/create', [PelleteController::class, "create"])->name('pellete.create');
    Route::post('/warehouses/pellete/store', [PelleteController::class, "store"])->name('pellete.store');
    Route::get('/warehouses/pellete/edit/{id}', [PelleteController::class, "edit"])->name('pellete.edit');
    Route::post('/warehouses/pellete/update/{id}', [PelleteController::class, "update"])->name('pellete.update');
    Route::get('/warehouses/pellete/destroy/{id}', [PelleteController::class, "destroy"])->name('pellete.destroy');

    //WAREHOUSE IN
    Route::get('/productions/in-progress/warehouse-in/index', [WarehouseInController::class, "index"])->name('warehouse-in.index');
    Route::get('/productions/in-progress/warehouse-in/create', [WarehouseInController::class, "create"])->name('warehouse-in.create');
    Route::post('/productions/in-progress/warehouse-in/store', [WarehouseInController::class, "store"])->name('warehouse-in.store');
    Route::get('/productions/in-progress/warehouse-in/edit/{id}', [WarehouseInController::class, "edit"])->name('warehouse-in.edit');
    Route::post('/productions/in-progress/warehouse-in/update/{id}', [WarehouseInController::class, "update"])->name('warehouse-in.update');
    Route::get('/productions/in-progress/warehouse-in/destroy/{id}', [WarehouseInController::class, "destroy"])->name('warehouse-in.destroy');
    Route::get('/productions/in-progress/warehouse/batches/get', [WarehouseInController::class, "batches"])->name('batches.warehouse.get');
    Route::get('/productions/in-progress/warehouse/pelletes/get', [WarehouseInController::class, "pelletes"])->name('pelletes.warehouse.get');

    //WAREHOUSE OUT
    Route::get('/productions/in-progress/warehouse-out/index', [WarehouseOutController::class, "index"])->name('warehouse-out.index');
    Route::get('/productions/in-progress/warehouse-out/create', [WarehouseOutController::class, "create"])->name('warehouse-out.create');
    Route::post('/productions/in-progress/warehouse-out/store', [WarehouseOutController::class, "store"])->name('warehouse-out.store');
    Route::get('/productions/in-progress/warehouse-out/edit/{id}', [WarehouseOutController::class, "edit"])->name('warehouse-out.edit');
    Route::post('/productions/in-progress/warehouse-out/update/{id}', [WarehouseOutController::class, "update"])->name('warehouse-out.update');
    Route::get('/productions/in-progress/warehouse-out/destroy/{id}', [WarehouseOutController::class, "destroy"])->name('warehouse-out.destroy');

    //CHECK PELLETE
    Route::get('/warehouses/check-pellete/index', [CheckPelleteController::class, "index"])->name('check-pellete.index');
    Route::get('/warehouses/check-pellete/get', [CheckPelleteController::class, "check"])->name('check-pellete.get');

});
