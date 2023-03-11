@extends('adminlte::page')

@section('title', 'Edit Companies')

@section('content_header')
    <h1>Edit Companies</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" name="name" value="{{ $company->name }}" class="form-control" placeholder="Enter name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email"  value="{{ $company->email }}" class="form-control" placeholder="Enter email">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="logo">Logo (minimum 100x100)</label>
            <input type="file" name="logo" class="form-control-file">
            @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <img src="{{ $company->logo }}" width="50">
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="text" value="{{ $company->website }}" name="website" class="form-control" placeholder="Enter website">
            @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@stop

