@extends('adminlte::page')

@section('title', 'Companies')

@section('content_header')
    <h1>Companies</h1>
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

    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" name="name" class="form-control" placeholder="Enter name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email">
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
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="text" name="website" class="form-control" placeholder="Enter website">
            @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@stop

