@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Subject</h3>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="unit_id">Unit</label>
                            <select name="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Kode Mapel</label>
                            <input type="text" name="code" class="form-control" id="code" placeholder="Enter kode mapel (e.g. MTK)">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Nama Mata Pelajaran</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter nama mata pelajaran" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
