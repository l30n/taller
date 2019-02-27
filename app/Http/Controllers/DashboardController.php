<?php
namespace App\Http\Controllers;

use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Sale::where('status', Sale::TERMINADO)->get()->sum('total');
        $sales = Sale::get()->groupBy('status');

        $statuses = [
            'Cotizacion',
            'En Proceso',
            'Terminado',
            'Cancelado',
        ];

        return view('dashboard.index', [
            'total' => $total,
            'sales' => $sales,
            'statuses' => $statuses,
        ]);
    }
}
