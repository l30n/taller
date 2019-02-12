@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Carros y sus Servicios</h2>
        </el-col>
        <el-col :span="5" style="text-align:right;">
            <el-button type="primary" icon="el-icon-circle-plus" v-on:click="goto('{{ route('carservices.create') }}')">
                Crear Servicios para Carros
            </el-button>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-carservices></list-carservices>
        </el-col>
    </el-row>
</el-main>
@endsection
