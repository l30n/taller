@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="12">
            <h2>Tablero</h2>
        </el-col>
        <el-col :span="12">
            <datepicker></datepicker>
        </el-col>
    </el-row>
    <br>
    <top-numbers :total="{{ $total }}" :sales="{{ $sales->toJson() }}"></top-numbers>
    <br>
    <el-row type="flex">
        <el-col :span="12">
            <dashboard-chart></dashboard-chart>
        </el-col>
        <el-col :span="12">
            <dashboard-table></dashboard-table>
        </el-col>
    </el-row>
</el-main>
@endsection