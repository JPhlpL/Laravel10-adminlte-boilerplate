@extends('adminlte::page')

@include('includes.header')

@section('content')
    <div class="container-fluid">
            <div class="row">
                <div class="col-4">
                    Form
                    <form name="create_permission_form" id="create_permission_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name
                                        <span class="text-danger"></span>
                                    </label>
                                    <input type="text" name="name" id="name" placeholder="Enter Permission Name" class="form-control" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="permission-btn" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-8">
                    Data
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table id="permission-table" class="table table-bordered table-hover data-table">
                                        <thead>
                                            <tr>
                                                <th class="bg-dark text-white">id</th>
                                                <th class="bg-dark text-white">Name</th>
                                                <th class="bg-dark text-white">Guard</th>
                                                <th class="bg-dark text-white">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="bg-dark text-white">id</th>
                                                <th class="bg-dark text-white">Name</th>
                                                <th class="bg-dark text-white">Guard</th>
                                                <th class="bg-dark text-white">Action</th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </div>
    {{-- Modal for Edit --}}
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update-permission-form">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="edit-name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="update-permission">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@include('includes.footer')
