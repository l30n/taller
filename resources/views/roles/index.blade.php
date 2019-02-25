@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Roles</h2>
        </el-col>
        <create-roles :permissions="{{ $permissions->toJson()}}"></create-roles>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-roles></list-roles>
        </el-col>
    </el-row>
</el-main>
@endsection
