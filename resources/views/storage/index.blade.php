@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl ">
    <div class="row justify-center">
        <example-component :storages="{{ json_encode(array_values($storages)) }}" />
    </div>
</div>
@endsection