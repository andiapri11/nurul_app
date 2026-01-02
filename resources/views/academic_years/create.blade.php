@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Academic Year</h3>
                </div>
                <form action="{{ route('academic-years.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_year">Tahun Awal (Start Year)</label>
                                    <input type="number" name="start_year" class="form-control" id="start_year" placeholder="YYYY" min="2000" max="2099" value="{{ old('start_year', date('Y')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_year">Tahun Akhir (End Year)</label>
                                    <input type="number" name="end_year" class="form-control" id="end_year" placeholder="YYYY" min="2000" max="2099" value="{{ old('end_year', date('Y') + 1) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                            <small class="form-text text-muted">Setting this to Active will automatically deactivate other academic years.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('academic-years.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
