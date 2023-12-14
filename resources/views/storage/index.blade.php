@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="row justify-center">
            <div class="m-5  flex justify-between">
                <a href="{{route('files.index') }}">Back</a>

                @if (isset($folder) && $folder['has_database_name'])
                    <form action="{{ route('files.show', ['folder' => $folder['has_database_name']]) }}" method="get">
                        @csrf
                        <input-search :folders='@json($folder)'></input-search>
                    </form>
                @endif
            </div>
            
            <example-component :storages='@json($storages)' :pagination='@json($storages ? $storages->onEachSide(5)->toArray() : [])'
                :folder='@json(isset($folder) ? $folder : [])' />

        </div>
    </div>
@endsection
