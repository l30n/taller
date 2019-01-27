@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Carros</h2>
        </el-col>
        <create-cars></create-cars>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-cars></list-cars>
        </el-col>
    </el-row>
</el-main>
@endsection
