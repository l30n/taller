<?php
namespace App\Http\Controllers;

use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $sales = Sale::get()->groupBy('status');

        $statuses = [
            'Cotizacion',
            'En Proceso',
            'Terminado',
            'Cancelado',
        ];

        return view('dashboard.index', [
            'sales' => $sales,
            'statuses' => $statuses,
        ]);
    }
}
