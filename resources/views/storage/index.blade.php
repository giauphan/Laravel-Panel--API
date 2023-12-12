@extends('layouts.app')

@section('content')
  

    <div class="mx-auto max-w-7xl">
        <div class="row justify-center">
            <example-component :storages='@json($storages)' :pagination='@json($storages ? $storages->onEachSide(5)->toArray() : [])' :preview='@json(isset($preview) ? $preview : [])' />

        </div>
    </div>
@endsection
