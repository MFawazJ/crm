@extends('adminlte::page')

@section('title', 'Companies')

@section('content_header')
    <h1>Companies</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Companies List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->email }}</td>
                        <td><img src="{{ $company->logo }}" width="50" height="50"></td>
                        <td>{{ $company->website }}</td>
                        <td>
                            <form action="{{ route('companies.destroy',$company->id) }}" method="Post">
                                @csrf
                                @method('DELETE')
                                <a class="btn btn-app bg-secondary" href="{{ route('companies.edit', $company->id) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="submit" class="btn btn-app bg-danger" href="{{ route('companies.destroy', $company->id) }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
            {{ $companies->links() }}
        </ul>
    </div>
@endsection
