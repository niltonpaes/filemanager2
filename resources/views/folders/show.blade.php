@extends('layouts.app')

@section('content')
<div class="container">

    <div class='mb-2'>
        <a href='/folders' class='btn btn-primary'><i class="fas fa-backward mr-2"></i>Back</a>
    </div>

    <h3>{{$folder->foldername}} - Files</h3>

    <div class='mb-2'>
        <files-list :folder_id="{{$folder->id}}" />
    </div>
</div>
@endsection