@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                 <h3 class="card-title">Atur Tahun Pelajaran dan Semester</h3>
            </div>
            <div class="card-tools">
                <a href="{{ route('academic-years.index') }}" class="btn btn-default btn-sm mr-2">
                    <i class="bi bi-arrow-clockwise"></i> Reload
                </a>
                <a href="{{ route('academic-years.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Tambah Tahun Pelajaran
                </a>
            </div>
        </div>
        <div class="card-body">
             @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="row">
                <!-- Tahun Pelajaran Table -->
                <div class="col-md-7">
                    <h5>Tahun Pelajaran</h5>
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th width="50px">No.</th>
                                <th>Tahun Pelajaran</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($academicYears as $key => $year)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $year->name }}</td>
                                    <td>
                                        @if($year->status == 'active')
                                            <span class="text-success font-weight-bold">
                                                <i class="bi bi-check-lg"></i> AKTIF
                                            </span>
                                        @else
                                            <form action="{{ route('academic-years.activate', $year->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm btn-block">AKTIFKAN</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('academic-years.destroy', $year->id) }}" method="POST">
                                            <a class="btn btn-warning btn-sm" href="{{ route('academic-years.edit', $year->id) }}">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                             @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Semester Table -->
                <div class="col-md-5">
                    <h5>Semester</h5>
                     <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th width="50px">No.</th>
                                <th>Semester</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semesters as $key => $semester)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $semester->name }}</td>
                                    <td>
                                         @if($semester->status == 'active')
                                            <span class="text-success font-weight-bold">
                                                <i class="bi bi-check-lg"></i> AKTIF
                                            </span>
                                        @else
                                            <form action="{{ route('semesters.activate', $semester->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm btn-block">AKTIFKAN</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
