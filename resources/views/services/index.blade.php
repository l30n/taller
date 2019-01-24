@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Servicios</h2>
        </el-col>
        <el-col :span="5" style="text-align:right;">
            <el-button type="primary" icon="el-icon-circle-plus" v-on:click="goto('{{ route('sales.create') }}')">
                Agregar un Servicio
            </el-button>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-services></list-services>
        </el-col>
    </el-row>
</el-main>
@endsection
