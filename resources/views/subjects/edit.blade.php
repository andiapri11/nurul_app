@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Subject</h3>
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
                
                <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="unit_id">Unit</label>
                            <select name="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $subject->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Kode Mapel</label>
                            <input type="text" name="code" value="{{ $subject->code }}" class="form-control" id="code" placeholder="Enter kode mapel (e.g. MTK)">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Nama Mata Pelajaran</label>
                            <input type="text" name="name" value="{{ $subject->name }}" class="form-control" id="name" placeholder="Enter nama mata pelajaran" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
