<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $start = date('Y-m-d 00:00:00', strtotime('last monday'));

        $total = Sale::where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->get()
            ->sum('total');

        $services = Sale::where('updated_at', '>=', $start)
            ->get()
            ->groupBy('status');

        $salesByStatus = Sale::select('status', DB::raw('SUM(total) AS total'))
            ->where('updated_at', '>=', $start)
            ->groupBy('status')
            ->get();

        $sales = Sale::select(DB::raw('DATE(done_on) AS date'), 'method', DB::raw('SUM(total) AS total'), DB::raw('COUNT(*) AS orders'))
            ->where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->groupBy(['date', 'method'])
            ->get();

        $users = Sale::select('user_id', DB::raw('SUM(total) AS total'), DB::raw('COUNT(*) AS orders'))
            ->where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->with('user')
            ->groupBy(['user_id'])
            ->get();

        $statuses = [
            'Cotizacion',
            'En Proceso',
            'Terminado',
            'Cancelado',
        ];

        return view('dashboard.index', [
            'total' => $total,
            'services' => $services,
            'salesByStatus' => $salesByStatus,
            'sales' => $sales,
            'sales' => $users,
            'statuses' => $statuses,
        ]);
    }

    public function get(Request $request)
    {
        $start = date('Y-m-d 00:00:00', strtotime($request->get('start')));
        $end = date('Y-m-d 23:59:59', strtotime($request->get('end')));

        $total = Sale::where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->where('done_on', '<=', $end)
            ->get()
            ->sum('total');

        $services = Sale::where('updated_at', '>=', $start)
            ->where('updated_at', '<=', $end)
            ->get()
            ->groupBy('status');
        $salesByStatus = Sale::select('status', DB::raw('SUM(total) AS total'))
            ->where('updated_at', '>=', $start)
            ->where('updated_at', '<=', $end)
            ->groupBy('status')
            ->get();
        $sales = Sale::select(DB::raw('DATE(done_on) AS date'), 'method', DB::raw('SUM(total) AS total'), DB::raw('COUNT(*) AS orders'))
            ->where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->where('done_on', '<=', $end)
            ->groupBy(['date', 'method'])
            ->get();

        $users = Sale::select('user_id', DB::raw('SUM(total) AS total'), DB::raw('COUNT(*) AS orders'))
            ->where('status', Sale::TERMINADO)
            ->where('done_on', '>=', $start)
            ->with('user')
            ->groupBy(['user_id'])
            ->get();

        return response()->json([
            'total' => $total,
            'services' => $services,
            'salesByStatus' => $salesByStatus,
            'sales' => $sales,
            'users' => $users,
        ]);
    }
}
