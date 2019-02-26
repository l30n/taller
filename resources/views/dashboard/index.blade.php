@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="24">
            <h2>Tablero</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        @foreach ($statuses as $key => $status)
        <el-col :span="6">
            <span class="bigNumber">{{ $sales->has($key) ? $sales->get($key)->count() : 0 }}</span>
            <p class="{{ str_replace(' ', '', strtolower($status)) }}">{{ $status }}</p>
        </el-col>
        @endforeach
    </el-row>
</el-main>
@endsection