<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DailyLogController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\MaintenanceLogController;
use App\Http\Controllers\Admin\VehicleAssignmentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ExpenseReceiptController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboard;
use App\Http\Controllers\Driver\DailyLogController as DriverDailyLogController;
use App\Http\Controllers\Driver\MaintenanceRequestController as DriverMaintenanceRequestController;
use App\Http\Controllers\Admin\MaintenanceRequestController as AdminMaintenanceRequestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Driver\ProfileController as DriverProfileController;
use App\Http\Controllers\Driver\AssignmentController;
use App\Http\Controllers\Driver\ExpenseReceiptController as DriverExpenseReceiptController;
/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Redirect Setelah Login
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return redirect()->route('redirect');
    })->name('dashboard');

    Route::get('/redirect', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('driver.dashboard');
    })->name('redirect');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [AdminDashboard::class, 'index']
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Vehicles
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'vehicles',
                VehicleController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Drivers
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'drivers',
                DriverController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Vehicle Assignments
            |--------------------------------------------------------------------------
            */

            Route::patch(
                '/assignments/{assignment}/finish',
                [VehicleAssignmentController::class, 'finish']
            )->name('assignments.finish');

            Route::resource(
                'assignments',
                VehicleAssignmentController::class
            )->only([
                'index',
                'create',
                'store',
                'show',
                'destroy',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Maintenance
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'maintenance',
                MaintenanceLogController::class
            )
                ->parameters([
                    'maintenance' => 'maintenanceLog',
                ])
                ->except([
                    'show',
                ]);
Route::delete(
    '/maintenance-requests/{maintenanceRequest}',
    [AdminMaintenanceRequestController::class, 'destroy']
)->name('maintenance-requests.destroy');
            /*
            |--------------------------------------------------------------------------
            | Daily Logs
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'logs',
                DailyLogController::class
            )
                ->parameters([
                    'logs' => 'dailyLog',
                ])
                ->except([
                    'show',
                ]);
                Route::post(
    '/logs/{dailyLog}/receipts',
    [ExpenseReceiptController::class, 'store']
)   ->name('logs.receipts.store');

Route::delete(
    '/receipts/{receipt}',
    [ExpenseReceiptController::class, 'destroy']
)->name('receipts.destroy');
Route::get(
    '/maintenance-requests',
    [AdminMaintenanceRequestController::class, 'index']
)->name('maintenance-requests.index');

Route::patch(
    '/maintenance-requests/{maintenanceRequest}/approve',
    [AdminMaintenanceRequestController::class, 'approve']
)->name('maintenance-requests.approve');

Route::patch(
    '/maintenance-requests/{maintenanceRequest}/reject',
    [AdminMaintenanceRequestController::class, 'reject']
)->name('maintenance-requests.reject');

Route::patch(
    '/maintenance-requests/{maintenanceRequest}/complete',
    [AdminMaintenanceRequestController::class, 'complete']
)->name('maintenance-requests.complete');
Route::get(
    '/reports',
    [ReportController::class, 'index']
)->name('reports.index');

Route::get(
    '/reports/export-excel',
    [ReportController::class, 'exportExcel']
)->name('reports.export-excel');

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::get(
    '/reports',
    [ReportController::class, 'index']
)->name('reports.index');

Route::get(
    '/reports/export-csv',
    [ReportController::class, 'exportCsv']
)->name('reports.export-csv');

Route::get(
    '/reports/export-excel',
    [ReportController::class, 'exportExcel']
)->name('reports.export-excel');

        });
        
/*
|--------------------------------------------------------------------------
| Driver Routes
|--------------------------------------------------------------------------
*/

Route::prefix('driver')
    ->middleware('role:driver')
    ->name('driver.')
    ->group(function () {

        Route::get(
            '/dashboard',
            [DriverDashboard::class, 'index']
        )->name('dashboard');

        Route::resource(
            'logs',
            DriverDailyLogController::class
        )->parameters([
            'logs' => 'dailyLog',
        ]);

        Route::post(
            '/logs/{dailyLog}/receipts',
            [DriverExpenseReceiptController::class, 'store']
        )->name('logs.receipts.store');

        Route::delete(
            '/receipts/{receipt}',
            [DriverExpenseReceiptController::class, 'destroy']
        )->name('receipts.destroy');

        Route::resource(
            'maintenance-requests',
            DriverMaintenanceRequestController::class
        )->parameters([
            'maintenance-requests' => 'maintenanceRequest',
        ])->except([
            'show',
        ]);

        Route::get(
            '/assignments/{assignment}/edit',
            [AssignmentController::class, 'edit']
        )->name('assignments.edit');

        Route::put(
            '/assignments/{assignment}',
            [AssignmentController::class, 'update']
        )->name('assignments.update');

        Route::get(
            '/profile',
            [DriverProfileController::class, 'show']
        )->name('profile.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});
