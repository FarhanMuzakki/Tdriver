<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\MaintenanceLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);

        $dailyLogsQuery = $this->dailyLogsQuery($filters);

        $dailyLogs = (clone $dailyLogsQuery)
            ->with([
                'driver',
                'vehicle',
                'receipts',
            ])
            ->latest('log_date')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $summaryLogs = (clone $dailyLogsQuery)->get();

        $totalTrips = $summaryLogs->count();

        $totalDistance = $summaryLogs->sum(function (DailyLog $dailyLog) {
            if (
                $dailyLog->start_odometer === null ||
                $dailyLog->end_odometer === null
            ) {
                return 0;
            }

            return max(
                0,
                $dailyLog->end_odometer - $dailyLog->start_odometer
            );
        });

        $totalFuelCost = (float) $summaryLogs->sum('fuel_cost');
        $totalTollCost = (float) $summaryLogs->sum('toll_cost');
        $totalParkingCost = (float) $summaryLogs->sum('parking_cost');

        $totalOperationalCost =
            $totalFuelCost +
            $totalTollCost +
            $totalParkingCost;

        $maintenanceQuery = MaintenanceLog::query()
            ->where('status', 'completed')
            ->when(
                $filters['start_date'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('service_date', '>=', $date)
            )
            ->when(
                $filters['end_date'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('service_date', '<=', $date)
            )
            ->when(
                $filters['vehicle_id'] ?? null,
                fn (Builder $query, string $vehicleId) =>
                    $query->where('vehicle_id', $vehicleId)
            );

        $totalMaintenanceCost = (float) $maintenanceQuery->sum('cost');

        $grandTotal =
            $totalOperationalCost +
            $totalMaintenanceCost;

        $vehicles = Vehicle::query()
            ->orderBy('plate_number')
            ->get();

        $drivers = User::query()
            ->where('role', 'driver')
            ->orderBy('name')
            ->get();

        return view(
            'admin.reports.index',
            compact(
                'dailyLogs',
                'vehicles',
                'drivers',
                'totalTrips',
                'totalDistance',
                'totalFuelCost',
                'totalTollCost',
                'totalParkingCost',
                'totalOperationalCost',
                'totalMaintenanceCost',
                'grandTotal'
            )
        );
    }

 public function exportExcel()
{
    $logs = DailyLog::with([
        'driver',
        'vehicle',
        'receipts',
    ])->latest('log_date')->get();

    $filename = 'Laporan_Operasional_' . now()->format('Ymd_His') . '.xls';

    $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => "attachment; filename={$filename}",
    ];

    $callback = function () use ($logs) {

        $file = fopen('php://output', 'w');

        fputcsv($file, ['PT TESCO INDOMARITIM']);
        fputcsv($file, ['LAPORAN OPERASIONAL KENDARAAN']);
        fputcsv($file, []);
        fputcsv($file, ['Tanggal Export', now()->format('d-m-Y H:i')]);
        fputcsv($file, []);

        fputcsv($file, [
            'No',
            'Tanggal',
            'Driver',
            'Kendaraan',
            'Tujuan',
            'Keperluan',
            'KM',
            'BBM',
            'Tol',
            'Parkir',
            'Total',
            'Jumlah Bukti',
        ]);

        $grandFuel = 0;
        $grandToll = 0;
        $grandParking = 0;
        $grandDistance = 0;
        $grandTotal = 0;

        foreach ($logs as $i => $log) {

            $distance = max(
                0,
                ($log->end_odometer ?? 0) - ($log->start_odometer ?? 0)
            );

            $total =
                $log->fuel_cost +
                $log->toll_cost +
                $log->parking_cost;

            $grandFuel += $log->fuel_cost;
            $grandToll += $log->toll_cost;
            $grandParking += $log->parking_cost;
            $grandDistance += $distance;
            $grandTotal += $total;

            $kendaraan = '-';

            if ($log->vehicle) {
                $kendaraan =
                    $log->vehicle->plate_number .
                    ' - ' .
                    $log->vehicle->type;
            }

            fputcsv($file, [
                $i + 1,
                optional($log->log_date)->format('d-m-Y'),
                $log->driver?->name ?? '-',
                $kendaraan,
                $log->destination,
                $log->purpose,
                $distance,
                $log->fuel_cost,
                $log->toll_cost,
                $log->parking_cost,
                $total,
                $log->receipts->count(),
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['RINGKASAN']);
        fputcsv($file, ['Jumlah Perjalanan', $logs->count()]);
        fputcsv($file, ['Total Kilometer', $grandDistance . ' KM']);
        fputcsv($file, ['Total BBM', $grandFuel]);
        fputcsv($file, ['Total Tol', $grandToll]);
        fputcsv($file, ['Total Parkir', $grandParking]);
        fputcsv($file, ['Grand Total', $grandTotal]);

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}
public function exportCsv(Request $request)
{
    $filters = $this->validateFilters($request);

    $logs = $this->dailyLogsQuery($filters)
        ->with([
            'driver',
            'vehicle',
            'receipts',
        ])
        ->latest('log_date')
        ->get();

    $filename = 'Laporan_Operasional_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
    ];

    $callback = function () use ($logs) {

        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'No',
            'Tanggal',
            'Driver',
            'Kendaraan',
            'Tujuan',
            'Keperluan',
            'KM',
            'BBM',
            'Tol',
            'Parkir',
            'Total',
        ]);

        foreach ($logs as $i => $log) {

            $distance = max(
                0,
                ($log->end_odometer ?? 0) - ($log->start_odometer ?? 0)
            );

            $kendaraan = '-';

            if ($log->vehicle) {
                $kendaraan =
                    $log->vehicle->plate_number .
                    ' - ' .
                    $log->vehicle->type;
            }

            fputcsv($file, [
                $i + 1,
                optional($log->log_date)->format('d-m-Y'),
                $log->driver?->name ?? '-',
                $kendaraan,
                $log->destination,
                $log->purpose,
                $distance,
                $log->fuel_cost,
                $log->toll_cost,
                $log->parking_cost,
                $log->fuel_cost +
                $log->toll_cost +
                $log->parking_cost,
            ]);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}
    private function dailyLogsQuery(array $filters): Builder
    {
        return DailyLog::query()
            ->when(
                $filters['start_date'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('log_date', '>=', $date)
            )
            ->when(
                $filters['end_date'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('log_date', '<=', $date)
            )
            ->when(
                $filters['vehicle_id'] ?? null,
                fn (Builder $query, string $vehicleId) =>
                    $query->where('vehicle_id', $vehicleId)
            )
            ->when(
                $filters['driver_id'] ?? null,
                fn (Builder $query, string $driverId) =>
                    $query->where('driver_id', $driverId)
            );
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'vehicle_id' => [
                'nullable',
                'uuid',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'nullable',
                'uuid',
                'exists:users,id',
            ],
        ]);
    }
}