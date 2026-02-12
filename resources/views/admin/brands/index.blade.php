@extends('admin.layout')

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Brands</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Add Brand</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Categories</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td>{{ $brand->id }}</td>
                                <td>
                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                        style="max-height: 40px; border-radius: 4px;">
                                </td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>{{ $brand->categories_count }}</td>
                                <td>{{ $brand->products_count }}</td>
                                <td>
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No brands found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $brands->links() }}
        </div>
    </div>
@endsection