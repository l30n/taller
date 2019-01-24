@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Articulos</h2>
        </el-col>
        <create-items></create-items>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-items></list-items>
        </el-col>
    </el-row>
</el-main>
@endsection
