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




                            <div class="form-group">
                                {{-- //TODO ADD SOME IMAGES ATTACHED but only limits --}}
                                <form name="create_task_form" id="create_task_form" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="task_title" id="task_title" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="col">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="task_desc" id="task_desc" required></textarea>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="mt-3 table table-bordered table-hover" id="autocomplete_table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="bg-dark text-white fixed-header">
                                                        <button type="button"
                                                            class="addNew border border-secondary text-dark rounded"
                                                            style="cursor:pointer;">
                                                            <i class="p-2 nav-icon fas fa-plus"></i>
                                                        </button>
                                                    </th>
                                                    <th style="min-width:300px;" class="bg-dark text-white">Item</th>
                                                    <th style="min-width:300px;" class="bg-dark text-white">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="row_1 " class="text-center">
                                                    <td><button id="delete_1"
                                                            class="delete_row border border-secondary text-dark rounded"
                                                            style="cursor:pointer;"><i
                                                                class="p-2 nav-icon fas fa-trash-alt"></i></button></td>
                                                    <td><input type="text" data-type="sub_task_title"
                                                            name="sub_task_title[]" id="sub_task_title_1"
                                                            class="form-control autocomplete_txt" autocomplete="off"></td>
                                                    <td>
                                                        <textarea class="form-control autocomplete_txt" data-type="sub_task_desc" name="sub_task_desc[]" id="sub_task_desc_1"
                                                            autocomplete="off"></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <footer>
                                        <button type="button" class="btn btn-info mt-3" id="submitBtn">Submit</button>
                                        <button type="button" class="btn btn-info mt-3" id="draftBtn">Save as Draft</button>
                                    </footer>
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
