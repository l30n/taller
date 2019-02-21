@extends('layouts.app') 
@section('content')
<create-carservices ref="create" :car-service="{{ $carService->toJson() }}" :items="{{ $items->toJson() }}"></create-carservices>
@endsection
