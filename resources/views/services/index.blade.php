@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Servicios</h2>
        </el-col>
        <create-services></create-services>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-services></list-services>
        </el-col>
    </el-row>
</el-main>
@endsection
