@extends('adminlte::page')

@include('includes.header')

@section('content')
    {{-- Content --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- TODO Create Table for displaying the files and can be installed or viewed --}}

                            <div class="card card-secondary collapsed-card">
                                <div class="card-header">
                                  <h3 class="card-title">Attachments</h3>

                                  <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="card-body">
                                    <div class="container mt-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="{{ route('dropzone.store', ['taskNumber' => $task->task_num]) }}" class="dropzone" id="myDropzone">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="mt-3 table table-bordered table-hover" id="attach_table">
                                            <thead>
                                                <tr class="text-center">

                                                    <th style="min-width:150px;" class="bg-dark text-white">Name</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Size</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Timestamp</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <form name="create_task_form" id="create_task_form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="task_title" id="task_title" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="col">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="task_desc" id="task_desc" readonly></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive mt-3">
                                        <table class="mt-3 table table-bordered table-hover" id="subtask_table">
                                            <thead>
                                                <tr class="text-center">

                                                    <th style="min-width:150px;" class="bg-dark text-white">Task No.</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Title</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Description</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Status</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Created At</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Update At</th>
                                                    <th style="min-width:150px;" class="bg-dark text-white">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Content --}}
@stop

@include('includes.footer')
