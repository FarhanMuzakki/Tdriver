<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\VehicleAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $driverId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Assignment aktif driver
        |--------------------------------------------------------------------------
        */

        $activeAssignments = VehicleAssignment::query()
            ->with('vehicle')
            ->where('driver_id', $driverId)
            ->where('status', 'active')
            ->latest('assigned_at')
            ->get();

        $mainAssignment = $activeAssignments->first();

        $activeVehicleIds = $activeAssignments
            ->pluck('vehicle_id')
            ->filter()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Aktivitas hari ini berdasarkan assignment aktif
        |--------------------------------------------------------------------------
        */

        $todayLogs = DailyLog::query()
            ->where('driver_id', $driverId)
            ->whereDate('log_date', today())
            ->when($activeVehicleIds->isNotEmpty(), function ($query) use ($activeVehicleIds) {
                $query->whereIn('vehicle_id', $activeVehicleIds);
            })
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

        $todayCost = $todayLogs->sum(function ($log) {
            return (float) $log->fuel_cost
                + (float) $log->toll_cost
                + (float) $log->parking_cost;
        });

        /*
        |--------------------------------------------------------------------------
        | Status tugas hari ini
        |--------------------------------------------------------------------------
        */

        $todayTasks = $activeAssignments->map(function ($assignment) use ($todayLogs) {
            $logs = $todayLogs->where('vehicle_id', $assignment->vehicle_id);

            $assignment->today_log_count = $logs->count();

            $assignment->today_distance = $logs->sum(function ($log) {
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

            $assignment->today_cost = $logs->sum(function ($log) {
                return (float) $log->fuel_cost
                    + (float) $log->toll_cost
                    + (float) $log->parking_cost;
            });

            $assignment->is_logged_today = $assignment->today_log_count > 0;

            return $assignment;
        });

        $todayAssignmentCount = $todayTasks->count();

        $todayCompletedCount = $todayTasks
            ->where('is_logged_today', true)
            ->count();

        $todayPendingCount = $todayTasks
            ->where('is_logged_today', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Riwayat log terbaru
        |--------------------------------------------------------------------------
        */

        $recentLogs = DailyLog::query()
            ->with([
                'vehicle',
                'receipts',
            ])
            ->where('driver_id', $driverId)
            ->latest('log_date')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view(
            'driver.dashboard',
            compact(
                'activeAssignments',
                'mainAssignment',
                'todayTasks',
                'todayAssignmentCount',
                'todayCompletedCount',
                'todayPendingCount',
                'todayLogCount',
                'todayDistance',
                'todayCost',
                'recentLogs'
            )
        );
    }
}