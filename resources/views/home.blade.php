@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome !</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <p>Hi, welcome to FileManager App v2.0. This version of the App comprises: </p>
                    <ul>
                        <li>Ability to register & login in the system using email & password, and social login via Google</li>
                        <li> Ability to upload files</li>
                        <li> Ability to delete files</li>
                        <li>Each file must only be accessible by the user who created it</li>
                        <li>PHP</li>
                        <li>Laravel</li>
                        <li>Mysql</li>
                        <li>Ability to organize files in folders</li>
                        <li>Clean UI/UX design</li>
                        <li>OAuth2</li>
                        <li>Usage of Vue <span class="badge badge-primary">New</span></li>
                        <li>Clear and usable APIs <span class="badge badge-primary">New</span></li>
                    </ul>

                    <p>Please, check out your personal menu on the top.</p>
                    <p>Your API Token is - {{ Auth::user()->api_token }}</p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection