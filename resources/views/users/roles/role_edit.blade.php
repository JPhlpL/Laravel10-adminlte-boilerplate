@extends('adminlte::page')

@include('includes.header')

@section('content')
   <div class="container-fluid">
        <div id="errorBox"></div>
        <form id="role-update-form" action="{{ route('users.roles.update', $role->id) }}" method="POST">
            @method('patch')
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label"> Name <span class="text-danger"> *</span></label>
                        <input type="text" name="name" class="form-control" placeholder="For e.g. Manager" value={{ucfirst($role->name)}}>
                        @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                        @endif
                    </div>
                    <label for="name" class="form-label"> Assign Permissions <span class="text-danger"> *</span></label>
                    <!--DataTable-->
                    <div class="table-responsive">
                        <table id="show-tblrole" class="table table-bordered table-hover data-table dtr-inline">
                            <thead>
                                <tr>
                                    <th class="bg-dark text-white chckbox-col">
                                        <input type="checkbox" id="all_permission" name="all_permission">
                                    </th>
                                    <th class="bg-dark text-white">Name</th>
                                    <th class="bg-dark text-white">Guard</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </div>
        </form>
   </div>
@stop

@include('includes.footer')
