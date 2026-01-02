@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3">Admin Management</h3>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Data Admin</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('administrators.index') }}" class="btn btn-default btn-sm mr-1">
                            <i class="bi bi-arrow-clockwise"></i> Reload
                        </a>
                        <a href="{{ route('administrators.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Tambah Admin
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="dataTables_length">
                                <label>
                                    Show 
                                    <select class="custom-select custom-select-sm form-control form-control-sm" style="width: auto; display: inline-block;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    entries
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                             <div class="dataTables_filter" style="text-align: right;">
                                <label>Search:
                                    <input type="search" class="form-control form-control-sm" placeholder="" style="display: inline-block; width: auto;">
                                </label>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50px">No.</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">Security PIN</th>
                                <th>Created On</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($administrators as $key => $admin)
                                @php
                                    $names = explode(' ', $admin->name, 2);
                                    $firstName = $names[0] ?? '';
                                    $lastName = $names[1] ?? '';
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $firstName }}</td>
                                    <td>{{ $lastName }}</td>
                                    <td>{{ $admin->username ?? '-' }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td class="text-center">
                                        <span class="font-weight-bold">{{ $admin->role }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($admin->security_pin)
                                            <span class="badge text-bg-success"><i class="bi bi-shield-check"></i> SET</span>
                                        @else
                                            <span class="badge text-bg-danger"><i class="bi bi-shield-x"></i> NOT SET</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="text-center">
                                        <span class="badge text-bg-success">Active</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('administrators.destroy',$admin->id) }}" method="POST">
                                            @if($key == 1) 
                                                 <!-- Assumption: First user is Super Admin/Current User, show settings icon or just edit -->
                                                 <a class="btn btn-primary btn-sm" href="{{ route('administrators.edit',$admin->id) }}"><i class="bi bi-gear-fill"></i></a>
                                            @else
                                                <a class="btn btn-warning btn-sm" href="{{ route('administrators.edit',$admin->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="bi bi-trash-fill"></i></button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                     <div class="d-flex justify-content-between mt-3">
                        <div>Showing 1 to {{ count($administrators) }} of {{ count($administrators) }} entries</div>
                        <!-- Simple pagination placeholder logic since we used get() not paginate() in controller -->
                        <ul class="pagination pagination-sm m-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
