@extends('layouts.app') 
@section('content')
<receipt-sales :sale="{{ !empty($sale) ? $sale->toJson() : " false " }}"></receipt-sales>
@endsection