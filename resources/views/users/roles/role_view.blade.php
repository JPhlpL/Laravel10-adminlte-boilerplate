@extends('adminlte::page')

@include('includes.header')

@section('content')
    <div class="container-fluid">
        <div id="errorBox"></div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h5>List</h5>
                </div>
                {{-- Open modal instead on another form --}}
                <a class="float-right btn btn-secondary" id="user-role-create" href="{{ route('users.roles.create') }}"><i class="fas fa-plus"></i> Add</a>
            </div>
            <div class="card-body">
                <!--DataTable-->
                <div class="table-responsive">
                    <table id="role-table" class="table table-bordered table-hover data-table dtr-inline">
                        <thead>
                            <tr class="text-center">
                                <th class="bg-dark text-white">ID</th>
                                <th class="bg-dark text-white">Name</th>
                                <th class="bg-dark text-white">Users</th>
                                <th class="bg-dark text-white">Permission</th>
                                <th class="bg-dark text-white">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="bg-dark text-white">ID</th>
                                <th class="bg-dark text-white">Name</th>
                                <th class="bg-dark text-white">Users</th>
                                <th class="bg-dark text-white">Permission</th>
                                <th class="bg-dark text-white"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@include('includes.footer')
