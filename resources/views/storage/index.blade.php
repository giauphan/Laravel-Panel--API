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

                <updaload-file>
                    <div class="w-full bg-white p-4">
                        <form action="{{route('files.upload') }}" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
                            @csrf
                            <div>
                                <input type="file" name="files" accept=".pdf, image/*">
                            </div>
                         <button> submit</button>
                        </form>
                    </div>
                </updaload-file>
            </div>
            
            <example-component :storages='@json($storages)' :pagination='@json($storages ? $storages->onEachSide(5)->toArray() : [])'
                :folder='@json(isset($folder) ? $folder : [])' />

        </div>
    </div>
@endsection
