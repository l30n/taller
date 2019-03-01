@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="12">
            <h2>Reporte de Ventas</h2>
        </el-col>
        <el-col :span="12">
            <datepicker></datepicker>
        </el-col>
    </el-row>
    <br>
    <top-numbers ref="dashboardNumbers" :total="{{ $total }}" :services="{{ $services->toJson() }}"></top-numbers>
    <br>
    <el-row type="flex">
        <el-col :span="12">
            <dashboard-chart ref="dashboardChart" :sales="{{ $salesByStatus->toJson() }}"></dashboard-chart>
        </el-col>
        <el-col :span="12">
            <dashboard-table ref="dashboardTable" :sales="{{ $sales->toJson() }}"></dashboard-table>
        </el-col>
    </el-row>
</el-main>
@endsection