<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Statistik kendaraan dan driver
        |--------------------------------------------------------------------------
        */

        $totalVehicles = Vehicle::count();

        $totalDrivers = User::query()
            ->where('role', 'driver')
            ->count();

        $activeDrivers = User::query()
            ->where('role', 'driver')
            ->count();

        $totalVehicles = Vehicle::count();

$vehiclesInUse = Vehicle::query()
    ->where('status', 'in_use')
    ->count();

$vehiclesMaintenance = Vehicle::query()
    ->where('status', 'maintenance')
    ->count();

$vehiclesAvailable = Vehicle::query()
    ->where('status', 'available')
    ->count();

        /*
        |--------------------------------------------------------------------------
        | Assignment
        |--------------------------------------------------------------------------
        */

        $activeAssignments = VehicleAssignment::query()
            ->where('status', 'active')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Pengajuan maintenance
        |--------------------------------------------------------------------------
        */

        $pendingMaintenanceRequests = MaintenanceRequest::query()
            ->where(
                'status',
                MaintenanceRequest::STATUS_PENDING
            )
            ->count();

        $approvedMaintenanceRequests = MaintenanceRequest::query()
            ->where(
                'status',
                MaintenanceRequest::STATUS_APPROVED
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Log perjalanan hari ini
        |--------------------------------------------------------------------------
        */

        $todayLogs = DailyLog::query()
            ->whereDate('log_date', today())
            ->get();

        $todayLogCount = $todayLogs->count();

        $todayDistance = $todayLogs->sum(function ($log) {
            if (
                $log->start_odometer === null ||
                $log->end_odometer === null
            ) {
                return 0;
            }

            return max(
                0,
                $log->end_odometer - $log->start_odometer
            );
        });

        $todayOperationalCost = $todayLogs->sum(function ($log) {
            return (float) $log->fuel_cost
                + (float) $log->toll_cost
                + (float) $log->parking_cost;
        });

        /*
        |--------------------------------------------------------------------------
        | Biaya maintenance bulan ini
        |--------------------------------------------------------------------------
        */

        $monthlyMaintenanceCost = MaintenanceLog::query()
            ->where('status', 'completed')
            ->whereYear('service_date', now()->year)
            ->whereMonth('service_date', now()->month)
            ->sum('cost');

        /*
        |--------------------------------------------------------------------------
        | Data terbaru
        |--------------------------------------------------------------------------
        */

        $recentMaintenanceRequests = MaintenanceRequest::query()
            ->with([
                'driver',
                'vehicle',
            ])
            ->latest('requested_at')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $recentLogs = DailyLog::query()
            ->with([
                'driver',
                'vehicle',
            ])
            ->latest('log_date')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalVehicles',
                'totalDrivers',
                'activeDrivers',
                'vehiclesInUse',
                'vehiclesMaintenance',
                'vehiclesAvailable',
                'activeAssignments',
                'pendingMaintenanceRequests',
                'approvedMaintenanceRequests',
                'todayLogCount',
                'todayDistance',
                'todayOperationalCost',
                'monthlyMaintenanceCost',
                'recentMaintenanceRequests',
                'recentLogs'
            )
        );
    }
}