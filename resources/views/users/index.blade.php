@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Usuarios</h2>
        </el-col>
        <create-users :roles="{{ $roles->toJson() }}"></create-users>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-users :roles="{{ $roles->toJson() }}"></list-users>
        </el-col>
    </el-row>
</el-main>
@endsection