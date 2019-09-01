@extends('layouts.app')

@section('content')
<div class="container">

    <div class='mb-2'>
        <a href='/folders' class='btn btn-primary'><i class="fas fa-backward mr-2"></i>Back</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Folder</div>

                <div class="card-body">
                    <form method="POST" action="/folders">

                        @csrf

                        <div class="form-group row">
                            <label for="foldername" class="col-md-4 col-form-label text-md-right">Folder Name</label>

                            <div class="col-md-6">
                                <input id="foldername" type="text" class="form-control @error('foldername') is-invalid @enderror" name="foldername" value="{{ old('foldername') }}" required>

                                @error('foldername')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2 lead"></i>Save
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection